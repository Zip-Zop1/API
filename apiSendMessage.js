// Defina o token de autenticação
const token = "TOKEN_API";

// Defina o número de telefone e a mensagem
const data = {
    number: '557183196364', // Número de telefone no formato internacional
    body: 'Ei Zip-Zop, esta é uma mensagem enviada apenas como texto pelo NodeJs!'
};

// URL do endpoint
const url = "URL_API";

// Função para enviar a requisição
async function sendMessage() {
    try {
        console.log("Iniciando requisição...");

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data) // Converter o objeto em JSON
        });

        // Verificar se a requisição foi bem-sucedida
        if (!response.ok) {
            throw new Error(`Erro: ${response.status} - ${response.statusText}`);
        }

        // Obter a resposta da API
        console.log("Resposta da API:", response);
    } catch (error) {
        // Exibir erros
        console.error("Erro ao enviar a mensagem:", error);
    } finally {
        console.log("Requisição finalizada.");
    }
}

// Executar a função
sendMessage();
