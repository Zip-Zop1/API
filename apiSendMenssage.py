import requests

# Mensagem e número do destinatário
body = "Ei Zip-Zop, esta é uma mensagem enviada apenas como texto pelo Python!"
number = "557183196364"

# URL do endpoint e token
url = 'URL_API'
token = "TOKEN_API"

# Dados do formulário (apenas texto)
data = {
    "number": number,
    "body": body,
}

# Cabeçalhos da requisição
headers = {
    "Authorization": f"Bearer {token}",
    "Content-Type": "application/json",  # Enviando os dados no formato JSON
}

# Enviar a requisição
try:
    response = requests.post(url, json=data, headers=headers)

    # Verifica se houve erro na requisição
    if response.status_code != 200:
        print(f"Erro na requisição: {response.status_code} - {response.reason}")
        print("Detalhes:", response.text)
        exit(1)

    # Exibe a resposta da API
    print("Resposta da API:")
    print(response.text)

except requests.RequestException as e:
    print("Erro ao enviar a mensagem:", e)
