package main
import (
  "fmt"
  "strings"
  "unicode"
)

func tokenize(inputString string) []string {
  return strings.FieldsFunc(inputString, func(r rune) bool {
    return !unicode.IsLetter(r) && !unicode.IsNumber(r)
  })
}

func toLowerCase(tokens []string) []string {
  var len_need = len(tokens)

  var tokenCopy = make( []string, len_need )
  
  for index := 0; index < len_need; index++ {
    tokenCopy[index] = strings.ToLower(tokens[index])
  }
  
  return tokenCopy
}

func main() {
  var search_str = "dshajdhsak HSDISHJDIO JSIDA  djaksljd"

  for _, token := range toLowerCase( tokenize(search_str) ) {
    fmt.Println(token) 
  }
}