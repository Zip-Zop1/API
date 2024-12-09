import os
import mimetypes
import requests

# Mensagem e número do destinatário
body = "Ei Zip-Zop, se liga só nesse arquivo enviado pelo Python!"
number = "557183196364"

# Caminho absoluto do arquivo
dir_path = os.path.dirname(os.path.abspath(__file__))  # Diretório do script atual
file_path = os.path.join(dir_path, "files/kapakapa.csv")  # Caminho completo do arquivo

# URL do endpoint e token
url = 'URL_API'
token = "TOKEN_API"

# Verifique se o arquivo existe
if not os.path.exists(file_path):
    print(f"Erro: Arquivo não encontrado: {file_path}")
    exit(1)

# Obtém o tipo MIME do arquivo
mime_type = mimetypes.guess_type(file_path)[0] or "application/octet-stream"
file_name = os.path.basename(file_path)

# Dados do formulário
with open(file_path, "rb") as file:  # Certifique-se de que o arquivo permanece aberto
    form_data = {
        "number": (None, number),
        "body": (None, body),
        "medias": (file_name, file, mime_type),
        "filename": (None, file_name),
    }

    # Cabeçalhos da requisição
    headers = {
        "Authorization": f"Bearer {token}",
    }

    # Enviar a requisição
    try:
        response = requests.post(url, files=form_data, headers=headers)

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
