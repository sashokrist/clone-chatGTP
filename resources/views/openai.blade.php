<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI Chat</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            color: #343a40;
            font-size: 18px;
            height: 300px;
            overflow-y: auto;
        }
        .response {
            background-color: #d4edda; /* Light grey background */
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }

    </style>
</head>

<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">OpenAI Chat</h1>
    <div class="card">
        <div class="card-body">
            <div id="chat" class="chat-box mt-3"></div>
            <textarea id="userInput" class="form-control mb-3" rows="3" placeholder="Type your message here..."></textarea>
            <button onclick="sendMessage()" class="btn btn-primary">Send</button>
        </div>
    </div>
</div>

<script>
    async function sendMessage() {
        const input = document.getElementById('userInput').value;
        const response = await fetch('/openai/completion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ messages: [{ role: 'user', content: input }] })
        });
        const data = await response.json();
        displayResponse(data);
    }

    function displayResponse(data) {
        const chatDiv = document.getElementById('chat');
        if (data.choices && data.choices.length > 0) {
            const assistantResponse = data.choices[0].message.content;
            const formattedResponse = formatResponse(assistantResponse);

            chatDiv.innerHTML += `<div class="card mb-3">
                                <div class="card-body">
                                    ${formattedResponse}
                                </div>
                              </div>`;
            chatDiv.scrollTop = chatDiv.scrollHeight; // Scroll to the bottom of the chat box
        }
    }

    function formatResponse(response) {
        // Split response into sentences
        const sentences = response.match(/[^\.!\?]+[\.!\?]+/g) || [response];

        return sentences.map(sentence => {
            // Check for lists, links, and programming code
            if (sentence.includes('- ')) { // Simple check for lists
                const items = sentence.split('- ').filter(item => item).map(item => `<li>${item.trim()}</li>`).join('');
                return `<ul>${items}</ul>`;
            } else if (sentence.includes('http://') || sentence.includes('https://')) { // Simple check for links
                const link = sentence.match(/(https?:\/\/[^\s]+)/g)[0];
                return sentence.replace(link, `<a href="${link}" target="_blank">${link}</a>`);
            } else if (sentence.startsWith('```') && sentence.endsWith('```')) { // Simple check for code
                const code = sentence.slice(3, -3).trim();
                return `<p style="font-family: monospace; font-weight: bold;">${code}</p>`;
            } else {
                return `<p>${sentence.trim()}</p>`;
            }
        }).join('');
    }

</script>
</body>
</html>
