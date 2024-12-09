// Importar bibliotecas necessárias (padrão do Node.js)
const fs = require("fs");
const path = require("path");
const { Blob } = require("buffer");
const mime = require("mime-types");

// Defina o token de autenticação
const token = "TOKEN_API";

// Defina o número de telefone e a mensagem
const body = "Ei Zip-Zop, se liga só nesse arquivo enviado pelo NodeJs!";
const number = "557183196364";

// Caminho do arquivo (em ambiente Node.js)
const filePath = "./files/kapakapa.csv"; // Substitua pelo caminho correto do arquivo no servidor

// URL do endpoint
const url = "URL_API";

// Verifique se o arquivo existe
if (!fs.existsSync(filePath)) {
  console.error("Erro: Arquivo não encontrado:", filePath);
  process.exit(1);
}

// Obtenha o tipo MIME e o nome do arquivo
const mimeType = mime.lookup(filePath) || "application/octet-stream";
const fileName = path.basename(filePath);

// Criar o FormData manualmente com Blobs
const createFormData = () => {
  const boundary = `----WebKitFormBoundary${Math.random().toString(36).substr(2, 16)}`;
  const delimiter = `--${boundary}`;
  const newline = "\r\n";

  let formData = "";

  // Campo 'number'
  formData += `${delimiter}${newline}`;
  formData += `Content-Disposition: form-data; name="number"${newline}${newline}`;
  formData += `${number}${newline}`;

  // Campo 'body'
  formData += `${delimiter}${newline}`;
  formData += `Content-Disposition: form-data; name="body"${newline}${newline}`;
  formData += `${body}${newline}`;

  // Campo 'medias' (arquivo)
  formData += `${delimiter}${newline}`;
  formData += `Content-Disposition: form-data; name="medias"; filename="${fileName}"${newline}`;
  formData += `Content-Type: ${mimeType}${newline}${newline}`;
  const fileBuffer = fs.readFileSync(filePath);
  const fileBlob = new Blob([fileBuffer]);
  formData += fileBlob + newline;

  // Campo 'filename'
  formData += `${delimiter}${newline}`;
  formData += `Content-Disposition: form-data; name="filename"${newline}${newline}`;
  formData += `${fileName}${newline}`;

  // Fechar o formulário
  formData += `${delimiter}--${newline}`;

  return { body: formData, boundary };
};

// Função para enviar a requisição
async function sendFile() {
  try {
    console.log("Iniciando requisição...");

    const { body, boundary } = createFormData();

    const response = await fetch(url, {
      method: "POST",
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": `multipart/form-data; boundary=${boundary}`,
      },
      body,
    });

    if (!response.ok) {
      throw new Error(`Erro na requisição: ${response.status} - ${response.statusText}`);
    }

    // Exibir a resposta da API
    const result = await response.text(); // Alterne para `response.json()` se a API retornar JSON
    console.log("Resposta da API:", response);
  } catch (error) {
    console.error("Erro ao enviar a mensagem:", error);
  } finally {
    console.log("Requisição finalizada.");
  }
}

// Executar a função
sendFile();
