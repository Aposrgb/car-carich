package main

import (
	"app/src/config"
	"bytes"
	"encoding/json"
	"fmt"
	"io"
	"io/ioutil"
	"net/http"
	"os"
	"strconv"
	"strings"
	"time"

	"github.com/bregydoc/gtranslate"
	"github.com/go-shiori/dom"
	"github.com/gosimple/slug"
	"golang.org/x/net/html"
	"golang.org/x/net/html/charset"
	"golang.org/x/text/language"
)

var (
	ELECTRONIC     int    = 1
	HYBRID         int    = 2
	PETROL         int    = 3
	ELECTRONIC_STR string = "чистый электрический"
	HYBRID_STR     string = "подключаемый гибрид"
)

var (
	PROJECT_DIR string = configInstance.GetParam("PROJECT_DIR")
	PUBLIC_PATH string = PROJECT_DIR + "public"
	SAVE_IMAGES string = "/uploads/car/images/"
)

var (
	SERVICE_LOGIN            string = configInstance.GetParam("SERVICE_CREDENTIALS_LOGIN")
	SERVICE_PASS             string = configInstance.GetParam("SERVICE_CREDENTIALS_PASS")
	SERVICE_HOST             string = configInstance.GetParam("SERVICE_HOST")
	SERVICE_ROUTE_CREATE_CAR string = configInstance.GetParam("SERVICE_ROUTE_CREATE_CAR")
)

var (
	configInstance config.Config = config.NewConfig()
	host           string        = configInstance.GetParam("FIRST_RESOURCE")
)

func main() {
	listRoute := "/china/list"
	url := host + listRoute
	totalCarsSize := 0
	nextPage := "page-item-next"
	for {
		if totalCarsSize > 300 {
			break
		}
		doc := sendRequestAndParseHtml(url)
		carinfos := dom.QuerySelectorAll(doc, ".carinfo")
		if len(carinfos) == 0 {
			continue
		}
		urls := make([]string, 0)
		for _, carinfo := range carinfos {
			urls = append(urls, dom.GetAttribute(carinfo, "href"))
		}
		if !createCarsByLinks(urls, &totalCarsSize) {
			continue
		}
		fmt.Println()
		fmt.Println("Total cars size:" + strconv.Itoa(totalCarsSize))
		url = host + dom.GetAttribute(dom.QuerySelector(doc, "."+nextPage), "href")
	}
}

func createCarsByLinks(links []string, totalCarsSize *int) bool {
	for _, link := range links {
		if strings.Contains(link, "https") {
			continue
		}
		fmt.Println()
		doc := sendRequestAndParseHtml(host + link)
		selector := dom.QuerySelector(doc, ".car-brand-name")
		if selector == nil {
			return false
		}
		carName := dom.InnerText(selector)
		carName = strings.TrimSpace(carName)
		carName = translateString(carName)
		mileageStr := dom.GetAttribute(dom.QuerySelector(doc, "input#car_mileage"), "value")
		var mileage int
		if strings.Contains(mileageStr, ".") {
			mileage = parseFromFloatToInt(mileageStr) * 100
		} else {
			mileage = parseInt(mileageStr) * 10_000
		}
		year := parseYear(dom.GetAttribute(dom.QuerySelector(doc, "input#car_firstregtime"), "value"))
		price := parseFromFloatToInt(dom.GetAttribute(dom.QuerySelector(doc, "input#car_price"), "value")) * 100
		engine, power, battery, mileageOneCharge, carType := getCarInfo(doc)
		power = translateString(power)
		selectorImages := dom.QuerySelector(doc, "#focus-1")
		selectorImages = dom.Children(dom.Children(dom.Children(selectorImages)[0])[2])[0]
		images := make([]string, 0)
		for _, selectorImage := range dom.Children(selectorImages) {
			if selectorImage.FirstChild.FirstChild == nil {
				continue
			}
			image := dom.GetAttribute(selectorImage.FirstChild.FirstChild, "src")
			images = append(images, image)
		}
		if len(images) == 0 {
			continue
		}

		fmt.Println(host + link)
		fmt.Println("name: " + carName)
		fmt.Println("mileage: " + strconv.Itoa(mileage))
		fmt.Println("year: " + strconv.Itoa(year))
		fmt.Println("price: " + strconv.Itoa(price))
		fmt.Println("engine: " + engine)
		fmt.Println("power: " + power)
		fmt.Println("battery: " + battery)
		fmt.Println("mileageOneCharge: " + mileageOneCharge)
		fmt.Println("type: " + translateString(carType))

		*totalCarsSize++
		sendCarToService(carName, mileage, year, price, engine, power, battery, mileageOneCharge, carType, images, totalCarsSize)
		fmt.Println("End sending")
		if *totalCarsSize > 300 {
			break
		}
	}
	return true
}

func sendCarToService(carName string, mileage int, year int, price int, engine string, power string, battery string, mileageOneCharge string, carType string, images []string, totalCarsSize *int) {
	var buf bytes.Buffer
	var carTypeInt int
	if carType == ELECTRONIC_STR {
		carTypeInt = ELECTRONIC
	} else if carType == HYBRID_STR {
		carTypeInt = HYBRID
	} else {
		carTypeInt = PETROL
	}
	images = saveImages(images[:3])
	err := json.NewEncoder(&buf).Encode(map[string]any{
		"name":             carName,
		"mileage":          mileage,
		"year":             year,
		"price":            price,
		"power":            power + " " + engine,
		"battery":          battery,
		"mileageOneCharge": mileageOneCharge,
		"type":             carTypeInt,
		"images":           images,
	})
	if err != nil {
		panic(err)
	}
	status, err := sendRequestPOSTAndGetBody(SERVICE_HOST+SERVICE_ROUTE_CREATE_CAR, &buf)
	if err != nil {
		panic(err)
	}
	if status > 204 {
		*totalCarsSize--
		fmt.Println("Error: " + strconv.Itoa(status))
	} else {
		fmt.Println("Success: " + strconv.Itoa(status))
	}

}

func saveImages(images []string) []string {
	urlImages := make([]string, 0)
	for i := 0; i < len(images); i++ {
		image := images[i]
		response, err := http.Get("https:" + image)
		if err != nil {
			i--
			time.Sleep(100 * time.Millisecond)
			continue
		}
		imageSplit := strings.Split(image, "/")
		imageName := imageSplit[len(imageSplit)-1]
		imageDetail := strings.Split(imageName, ".")
		imageName = Uniqid(slug.Make(imageDetail[0])) + "." + imageDetail[1]
		file, err := os.Create(PUBLIC_PATH + SAVE_IMAGES + imageName)
		if err != nil {
			panic(err)
		}
		_, err = io.Copy(file, response.Body)
		file.Close()
		response.Body.Close()
		if err != nil {
			panic(err)
		}
		urlImages = append(urlImages, SAVE_IMAGES+imageName)
	}
	return urlImages
}

func getCarInfo(doc *html.Node) (string, string, string, string, string) {
	carDetailInfo := dom.QuerySelector(doc, ".all-basic-content.fn-clear")
	engineInfo := dom.Children(carDetailInfo)[0]
	engineInfo = dom.Children(engineInfo)[4]
	var engine string
	fmt.Sscanf(dom.InnerText(engineInfo), "排 量 %s", &engine)

	powerInfo := dom.Children(carDetailInfo)[2]
	var tmp string
	var power string = "-"
	fmt.Sscanf(dom.InnerText(dom.Children(powerInfo)[0]), "发 动 机 %s %s", &tmp, &power)

	var battery string
	fmt.Sscanf(dom.InnerText(engineInfo), "NEDC纯电续航里程 %s", &battery)

	var mileageOneCharge string
	if len(dom.Children(powerInfo)) > 5 {
		fmt.Sscanf(dom.InnerText(dom.Children(powerInfo)[5]), "标准容量 %s", &mileageOneCharge)
	}
	typeInfo := dom.Children(dom.Children(carDetailInfo)[0])[3]
	var carType string = "-"
	fmt.Sscanf(dom.InnerText(typeInfo), "燃料类型 %s", &carType)

	return engine, power, battery, mileageOneCharge, carType
}

func Uniqid(prefix string) string {
	now := time.Now()
	sec := now.Unix()
	usec := now.UnixNano() % 0x100000
	return fmt.Sprintf("%s%08x%05x", prefix, sec, usec)
}

func parseYear(year string) int {
	var i int
	_, err := fmt.Sscanf(year, "%4d/", &i)
	if err != nil {
		panic(err)
	}
	return i
}

func parseFromFloatToInt(number string) int {
	var i, j int
	_, err := fmt.Sscanf(number, "%d.%d", &i, &j)
	if err != nil {
		panic(err)
	}
	return i*100 + j
}

func parseInt(number string) int {
	integerNumber, err := strconv.Atoi(number)
	if err != nil {
		panic(err)
	}
	return int(integerNumber)
}

func sendRequestAndParseHtml(url string) *html.Node {
	var body string
	for {
		body, _, _ = sendRequestAndGetBody(url)
		if len(body) == 0 {
			continue
		} else {
			break
		}
	}
	doc, err := parseHTMLSource(body)
	if err != nil {
		panic(err)
	}
	return doc
}

func sendRequestAndGetBody(url string) (string, int, error) {
	resp, err := http.Get(url)
	if err != nil {
		return "", 0, err
	}
	responseBody, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		return "", 0, err
	}
	body := string(responseBody)
	body = convertToUTF8(body, "GBK")
	return body, resp.StatusCode, nil
}

func sendRequestPOSTAndGetBody(url string, bodyReq io.Reader) (int, error) {
	client := &http.Client{}
	req, _ := http.NewRequest("POST", url, bodyReq)
	req.Header.Set("login", SERVICE_LOGIN)
	req.Header.Set("password", SERVICE_PASS)
	responseBody, err := client.Do(req)
	if err != nil {
		return 0, err
	}
	return responseBody.StatusCode, nil
}

func translateString(toTranslate string) string {
	translated, err := gtranslate.Translate(toTranslate, language.Chinese, language.Russian)
	if err != nil {
		panic(err)
	}
	return translated
}

func parseHTMLSource(htmlSource string) (*html.Node, error) {
	doc, err := html.Parse(strings.NewReader(htmlSource))
	if err != nil {
		return nil, err
	}
	return doc, nil
}

func convertToUTF8(str string, origEncoding string) string {
	strBytes := []byte(str)
	byteReader := bytes.NewReader(strBytes)
	reader, _ := charset.NewReaderLabel(origEncoding, byteReader)
	strBytes, _ = ioutil.ReadAll(reader)
	return string(strBytes)
}
