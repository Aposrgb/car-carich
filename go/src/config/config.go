package config

import (
	"fmt"
	"os"

	"github.com/joho/godotenv"
)

type Config struct{}

func NewConfig() Config {
	configInstance := Config{}
	configInstance.Init()
	return configInstance
}

func (c *Config) Init() {
	err := godotenv.Load(".env.local")
	if err != nil {
		err := godotenv.Load()
		if err != nil {
			fmt.Println(err)
			panic("Error loading .env file")
		}
	}

}

func (c *Config) GetParam(paramName string) string {
	param, err := os.LookupEnv(paramName)
	if !err {
		fmt.Println("Error get value from .env file param: " + paramName)
	}
	return param
}
