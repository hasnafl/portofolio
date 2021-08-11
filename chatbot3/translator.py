from googletrans import Translator

translator = Translator()

result = translator.translate('Hi. Nice to meet you', src='english', dest='id')

print(result.src)
print(result.dest)
print(result.text)