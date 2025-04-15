<?php
// chatbot.php

Functions::startSessionIfNotStarted();
$database = new conn();
$conn = $database->conn;

$user_status = 'offline';
$accountnum = '';
$billData = [];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = Functions::getUserNum($conn, $user_id);
    if ($user) {
        $user_status = $user['status'];
        $accountnum = $user['accountnum'] ?? '';
        
        if ($accountnum) {
            $billInquiryUrl = "http://10.0.1.247:8090/api/bill_inquiry/$accountnum";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $billInquiryUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $billResponse = curl_exec($ch);
            curl_close($ch);
            $billData = json_decode($billResponse, true)[0][0] ?? [];
        }
    }
}

function formatMonth($billingMonth) {
    return date("F", mktime(0, 0, 0, substr($billingMonth, 4, 2), 1, substr($billingMonth, 0, 4)));
}
?>

<!-- Chatbot Toggle Button -->
<button id="chatbot-toggle" class="fixed bottom-4 right-4 bg-gradient-to-r from-indigo-500 to-blue-600 text-white px-5 py-3 rounded-full shadow-lg hover:from-indigo-600 hover:to-blue-700 transition-all duration-300 z-50 flex items-center gap-2">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5v-2a2 2 0 012-2h10a2 2 0 012 2v2h-4M12 4a8 8 0 100 16 8 8 0 000-16z"></path></svg>
    Chat Now
</button>

<!-- Chatbot Container -->
<div id="chatbot-container" class="hidden fixed bottom-4 right-4 w-full max-w-[420px] bg-white rounded-xl shadow-2xl z-50 transition-all duration-300 md:max-w-md">
    <div class="bg-yellow-500 text-white p-4 rounded-t-xl flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-2.761 0-5 2.239-5 5v2h10v-2c0-2.761-2.239-5-5-5z"></path></svg>
            <span class="font-semibold text-lg">BILECO Assistant</span>
        </div>
        <button id="chatbot-close" class="text-white text-2xl font-bold hover:text-gray-200 transition-colors">×</button>
    </div>
    <div id="chatbot-messages" class="p-4 h-[400px] overflow-y-auto bg-gray-50 scrollbar-hidden">
        <!-- Messages will be added here -->
    </div>
    <div class="p-4 bg-white border-t border-gray-100">
        <div id="suggestions" class="flex gap-2 mb-3 overflow-x-auto scrollbar-hidden">
            <button class="suggestion-chip">Check Bill</button>
            <button class="suggestion-chip">Pay Bill</button>
            <button class="suggestion-chip">Report Outage</button>
            <button class="suggestion-chip">Contact Us</button>
        </div>
        <div class="flex gap-2">
            <input type="text" id="message-input" placeholder="Ask me anything..." class="flex-1 p-3 bg-gray-100 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-gray-700 placeholder-gray-400">
            <button id="send-button" class="bg-indigo-500 text-white px-4 py-2 rounded-full hover:bg-indigo-600 transition-all flex items-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </div>
    </div>
</div>

<style>
    /* Hide Scrollbar */
    .scrollbar-hidden {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }
    .scrollbar-hidden::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }

    /* Animations */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .message-bubble {
        animation: slideUp 0.2s ease-out;
    }

    /* Message Styles */
    .user-message {
        max-width: 80%;
        margin-left: auto;
        background: linear-gradient(135deg,rgb(8, 3, 108),rgb(9, 37, 83));
        color: white;
        padding: 10px 14px;
        border-radius: 16px 16px 4px 16px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .bot-message {
        max-width: 80%;
        margin-right: auto;
        background: #edf2f7;
        color: #1f2937;
        padding: 10px 14px;
        border-radius: 16px 16px 16px 4px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .timestamp {
        font-size: 0.75rem;
        color:rgb(148, 149, 150);
        margin-top: 2px;
    }

    .typing-indicator {
        background: #edf2f7;
        color: #6b7280;
        padding: 10px 14px;
        border-radius: 16px 16px 16px 4px;
        max-width: 80%;
        margin-bottom: 10px;
    }
    .typing-indicator::after {
        content: '...';
        animation: typing 1s infinite;
    }
    @keyframes typing {
        0% { content: '.'; }
        33% { content: '..'; }
        66% { content: '...'; }
    }

    /* Suggestion Chips */
    .suggestion-chip {
        background: #e5e7eb;
        color: #374151;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.875rem;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s;
    }
    .suggestion-chip:hover {
        background: #d1d5db;
    }
    .suggestion-chip:active {
        background: #9ca3af;
    }

    /* Responsive Design */
    @media (max-width: 640px) {
        #chatbot-container {
            width: 100%;
            max-width: none;
            bottom: 0;
            right: 0;
            border-radius: 0;
            height: 100vh;
        }
        #chatbot-messages {
            height: calc(100vh - 180px);
        }
        #chatbot-toggle {
            bottom: 8px;
            right: 8px;
        }
    }
</style>

<script>
    // Pass PHP data to JavaScript
    const isLoggedIn = <?php echo json_encode($user_status !== 'offline'); ?>;
    const billData = <?php echo json_encode($billData); ?>;

    // Chatbot responses
    const responses = {
        'hi': 'Hello! Welcome to BILECO Assistant. How can I assist you today?',
        'how are you': 'Icdd I’m an AI, so I’m always good! How can I help you with BILECO services?',
        'bye': 'See you later! Reach out anytime for assistance.',
        'what time is it': 'I don’t have a watch, but I’m here 24/7 for your electric queries!',
        'bill': isLoggedIn ? `Your bill for ${billData['Billing Month'] ? formatMonth(billData['Billing Month']) : 'N/A'} is ₱${billData['Current Month Bill'] ? Number(billData['Current Month Bill']).toFixed(2) : 'N/A'}.` : 'Please log in to view your bill.',
        'check bill': isLoggedIn ? `Your bill for ${billData['Billing Month'] ? formatMonth(billData['Billing Month']) : 'N/A'} is ₱${billData['Current Month Bill'] ? Number(billData['Current Month Bill']).toFixed(2) : 'N/A'}.` : 'Please log in to view your bill.',
        'how can i view my bill': isLoggedIn ? 'Log in to bileco.net or visit our office with your account number.' : 'Please log in first.',
        'what’s my current balance': isLoggedIn ? `Your balance is ₱${billData['Running Balance'] ? Number(billData['Running Balance']).toFixed(2) : '0.00'}.` : 'Please log in to check your balance.',
        'pay': 'To pay your bill, ask "How do I pay my bill?" for options!',
        'pay bill': 'You can pay your bill online via the BILECO portal, at our office, or through authorized payment centers like GCash or banks.',
        'how do i pay my bill': 'Pay online at bileco.net, via GCash, or at our office or authorized centers.',
        'outage': 'Report a power outage by saying "Report an outage" or check status with "Is there an outage?"',
        'report outage': 'Please provide your location or account number to report an outage, or call our hotline.',
        'report an outage': 'Please provide your location or account number to report an outage, or call our hotline.',
        'is there an outage': 'Check bileco.net for updates or call our support line for real-time info.',
        'contact': 'For contact info, say "What’s your contact number?" or "How do I reach you?"',
        'contact us': 'You can reach BILECO at (insert number) or email us at info@bileco.net.',
        'what’s your contact number': 'Reach us at (insert number) or email info@bileco.net.',
        'how do i reach you': 'Contact us at (insert number), info@bileco.net, or visit Brgy. Caraycaray, Naval, Biliran.',
        'rates': 'Ask "What are the current rates?" to learn about our electricity pricing!',
        'what are the current rates': 'Rates vary by usage. Visit bileco.net for the latest tariff details.',
        'apply': 'To apply for service, say "How do I apply for service?"',
        'how do i apply for service': 'Apply online at bileco.net or visit our office with ID and proof of residence.',
        'default': 'Not sure how to help with that. Try asking about bills, payments, or outages!'
    };

    // Format month function
    function formatMonth(billingMonth) {
        const month = billingMonth.substring(4, 6);
        const year = billingMonth.substring(0, 4);
        return new Date(year, month - 1).toLocaleString('default', { month: 'long' });
    }

    // DOM elements
    const toggleBtn = document.getElementById('chatbot-toggle');
    const chatbotContainer = document.getElementById('chatbot-container');
    const closeBtn = document.getElementById('chatbot-close');
    const messagesDiv = document.getElementById('chatbot-messages');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const suggestions = document.querySelectorAll('.suggestion-chip');

    // Chat history
    let chatHistory = JSON.parse(sessionStorage.getItem('chatHistory')) || [];

    // Save chat history
    const saveChatHistory = () => {
        sessionStorage.setItem('chatHistory', JSON.stringify(chatHistory));
    };

    // Display chat history
    const displayChatHistory = () => {
        messagesDiv.innerHTML = '';
        chatHistory.forEach(chat => {
            const className = chat.type === 'user' ? 'user-message' : 'bot-message';
            addMessage(chat.content, className, chat.timestamp);
        });
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    };

    // Add message with timestamp
    const addMessage = (text, className, timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })) => {
        if (!text) return;
        const messageDiv = document.createElement('div');
        messageDiv.className = `${className} message-bubble`;
        messageDiv.innerHTML = `${text}<div class="timestamp">${timestamp}</div>`;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    };

    // Initialize welcome message if no history exists
    if (!chatHistory.length) {
        const welcomeMessage = 'Hi! I’m BILECO Assistant. How can I help you today?';
        const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        chatHistory.push({ type: 'bot', content: welcomeMessage, timestamp });
        saveChatHistory();
    }

    // Toggle chatbot
    toggleBtn.addEventListener('click', () => {
        chatbotContainer.classList.toggle('hidden');
        toggleBtn.classList.toggle('hidden');
        if (!chatbotContainer.classList.contains('hidden')) {
            displayChatHistory();
        }
    });

    closeBtn.addEventListener('click', () => {
        chatbotContainer.classList.add('hidden');
        toggleBtn.classList.remove('hidden');
    });

    // Send message
    const sendMessage = (messageText) => {
        const message = messageText || messageInput.value.trim();
        if (!message) return;

        const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        addMessage(message, 'user-message', timestamp);
        chatHistory.push({ type: 'user', content: message, timestamp });
        saveChatHistory();

        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        typingDiv.textContent = 'Typing';
        messagesDiv.appendChild(typingDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;

        setTimeout(() => {
            const response = responses[Object.keys(responses).find(key => message.toLowerCase().includes(key))] || responses['default'];
            messagesDiv.removeChild(typingDiv);
            addMessage(response, 'bot-message', timestamp);
            chatHistory.push({ type: 'bot', content: response, timestamp });
            saveChatHistory();
        }, 1000);

        messageInput.value = '';
    };

    sendButton.addEventListener('click', () => sendMessage());
    messageInput.addEventListener('keypress', (e) => e.key === 'Enter' && sendMessage());

    // Handle suggestion clicks
    suggestions.forEach(chip => {
        chip.addEventListener('click', () => {
            const suggestionText = chip.textContent.toLowerCase();
            sendMessage(suggestionText);
        });
    });

    // Initial display of chat history
    displayChatHistory();
</script>