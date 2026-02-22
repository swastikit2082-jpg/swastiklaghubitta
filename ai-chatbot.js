// ========================================
// ADVANCED AI CHATBOT FOR SWASTIK MICROFINANCE
// ========================================

// Chatbot state management
let aiChatbotState = {
    isOpen: false,
    isMinimized: false,
    isListening: false,
    messages: [],
    recognition: null
};

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initAIChatbot();
});

function initAIChatbot() {
    // Load chat history from localStorage
    loadChatHistory();

    // Initialize speech recognition if available
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        aiChatbotState.recognition = new SpeechRecognition();
        aiChatbotState.recognition.continuous = false;
        aiChatbotState.recognition.interimResults = false;
        aiChatbotState.recognition.lang = 'ne-NP'; // Nepali language

        aiChatbotState.recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            const inputField = document.getElementById('aiChatbotInput');
            if (inputField) {
                inputField.value = transcript;
                sendAIChatMessage();
            }
        };

        aiChatbotState.recognition.onend = function() {
            toggleVoiceInput(false);
        };
    }

    // Auto-show chatbot after 30 seconds for first-time users
    setTimeout(() => {
        if (!aiChatbotState.isOpen && !localStorage.getItem('aiChatbotOpened')) {
            const toggle = document.getElementById('aiChatbotToggle');
            if (toggle) {
                toggle.style.animation = 'bounce 1s ease-in-out';
                setTimeout(() => toggle.style.animation = '', 1000);
            }
        }
    }, 30000);
}

function toggleAIChatbot() {
    const windowEl = document.getElementById('aiChatbotWindow');
    const toggle = document.getElementById('aiChatbotToggle');
    const badge = document.getElementById('aiChatbotBadge');

    aiChatbotState.isOpen = !aiChatbotState.isOpen;

    if (aiChatbotState.isOpen) {
        if (windowEl) windowEl.classList.add('show');
        if (toggle) {
            toggle.style.transform = 'scale(0.9)';
            setTimeout(() => toggle.style.transform = 'scale(1)', 200);
        }

        // Hide badge after first open
        if (badge) {
            badge.style.display = 'none';
        }

        // Mark as opened
        localStorage.setItem('aiChatbotOpened', 'true');

        // Focus input
        setTimeout(() => {
            const input = document.getElementById('aiChatbotInput');
            if (input) input.focus();
        }, 300);
    } else {
        if (windowEl) windowEl.classList.remove('show');
        closeAIChatbot();
    }
}

function minimizeAIChatbot() {
    const windowEl = document.getElementById('aiChatbotWindow');
    aiChatbotState.isMinimized = !aiChatbotState.isMinimized;

    if (aiChatbotState.isMinimized) {
        if (windowEl) {
            windowEl.style.height = '60px';
            windowEl.style.overflow = 'hidden';
        }
    } else {
        if (windowEl) {
            windowEl.style.height = '520px';
            windowEl.style.overflow = 'visible';
        }
    }
}

function closeAIChatbot() {
    const windowEl = document.getElementById('aiChatbotWindow');
    if (windowEl) {
        windowEl.classList.remove('show');
    }
    aiChatbotState.isOpen = false;
    aiChatbotState.isMinimized = false;
    if (windowEl) {
        windowEl.style.height = '520px';
        windowEl.style.overflow = 'visible';
    }
}

function handleAIChatKeyPress(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        sendAIChatMessage();
    }
}

function quickAIReply(message) {
    const input = document.getElementById('aiChatbotInput');
    if (input) {
        input.value = message;
        sendAIChatMessage();
    }
}

function sendAIChatMessage() {
    const input = document.getElementById('aiChatbotInput');
    if (!input) return;
    
    const message = input.value.trim();

    if (!message) return;

    // Add user message
    addAIMessage(message, 'user');

    // Clear input
    input.value = '';

    // Show typing indicator
    showAITyping();

    // Generate response after delay
    setTimeout(() => {
        hideAITyping();
        const response = generateAIResponse(message);
        addAIMessage(response, 'bot');
        saveChatHistory();
    }, 1000 + Math.random() * 2000); // Random delay for realism
}

function addAIMessage(content, type) {
    const messagesDiv = document.getElementById('aiChatbotMessages');
    if (!messagesDiv) return;
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `ai-chatbot-message ai-chatbot-message-${type}`;

    const avatar = type === 'bot' ? '🤖' : '👤';
    const time = new Date().toLocaleTimeString('ne-NP', {
        hour: '2-digit',
        minute: '2-digit'
    });

    messageDiv.innerHTML = `
        <div class="ai-chatbot-message-avatar">${avatar}</div>
        <div class="ai-chatbot-message-content">
            <div class="ai-chatbot-message-bubble">
                ${content}
            </div>
            <span class="ai-chatbot-message-time">${time}</span>
        </div>
    `;

    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;

    // Store message
    aiChatbotState.messages.push({ content, type, time });
}

function showAITyping() {
    const typingDiv = document.getElementById('aiChatbotTyping');
    if (typingDiv) {
        typingDiv.style.display = 'block';
        typingDiv.scrollIntoView({ behavior: 'smooth' });
    }
}

function hideAITyping() {
    const typingDiv = document.getElementById('aiChatbotTyping');
    if (typingDiv) {
        typingDiv.style.display = 'none';
    }
}

function generateAIResponse(message) {
    const lowerMessage = message.toLowerCase();

    // Greeting responses
    if (lowerMessage.includes('नमस्ते') || lowerMessage.includes('नमस्कार') ||
        lowerMessage.includes('hello') || lowerMessage.includes('hi') || lowerMessage.includes('salam')) {
        return `नमस्ते! 👋<br><br>स्वस्तिक लघुवित्तमा स्वागत छ! म तपाईंको AI सहायक हुँ।<br><br>तपाईंलाई कस्तो सहायता चाहिएको छ?<br>• ऋणका बारेमा जानकारी<br>• ब्याजदरहरू<br>• आवेदन प्रक्रिया<br>• शाखा कार्यालयहरू<br><br>कृपया आफ्नो प्रश्न सोध्नुहोस्। 💼`;
    }

    // Loan related
    if (lowerMessage.includes('ऋण') || lowerMessage.includes('loan') || lowerMessage.includes('gada')) {
        if (lowerMessage.includes('कसरी') || lowerMessage.includes('कस्ले') || lowerMessage.includes('how') || lowerMessage.includes('payo')) {
            return `ऋण लिनका लागि यहाँ के गर्नुपर्छ:<br><br>📋 <strong>आवश्यक कागजातहरू:</strong><br>• नेपाली नागरिकता प्रमाणपत्र<br>• स्थायी ठेगाना प्रमाण<br>• आय प्रमाण (नागरिकता अनुसार)<br>• दुईवटा हालसालैका फोटो<br><br>💰 <strong>ऋण प्रकारहरू:</strong><br>• व्यापारिक ऋण (५० लाख सम्म)<br>• कृषि ऋण (विशेष सुविधा)<br>• घरायसी ऋण (१० वर्ष सम्म)<br>• महिला समूह ऋण<br><br>📞 <strong>सम्पर्क गर्नुहोस्:</strong> 033-563067<br><br><a href="contact.html" style="color:#1e3c72;">अब आवेदन गर्नुहोस् →</a>`;
        }
        return `हामीले विभिन्न प्रकारका ऋण सुविधाहरू प्रदान गर्दछौं:<br><br>💼 <strong>व्यापारिक ऋण:</strong> ५ लाख देखि ५० लाख सम्म<br>🌾 <strong>कृषि ऋण:</strong> मौसमी र दीर्घकालीन<br>🏠 <strong>घरायसी ऋण:</strong> निर्माण र मर्मतका लागि<br>👥 <strong>महिला समूह ऋण:</strong> समूह ग्यारेन्टीमा<br><br>विस्तृत जानकारीका लागि "ऋण कसरी लिने?" भन्नुहोस्।`;
    }

    // Interest rates
    if (lowerMessage.includes('ब्याज') || lowerMessage.includes('interest') ||
        lowerMessage.includes('dar') || lowerMessage.includes('rate')) {
        return `हाम्रा प्रतिस्पर्धी ब्याजदरहरू:<br><br>📊 <strong>व्यापारिक ऋण:</strong> १२-१८% प्रति वर्ष<br>🌾 <strong>कृषि ऋण:</strong> ८-१२% प्रति वर्ष<br>🏠 <strong>घरायसी ऋण:</strong> १०-१५% प्रति वर्ष<br>👥 <strong>महिला समूह ऋण:</strong> ८-१२% प्रति वर्ष<br><br>⚡ <strong>विशेष सुविधाहरू:</strong><br>• छिटो स्वीकृति (२ घण्टा)<br>• न्यूनतम कागजात<br>• सहज किस्ता<br>• समयमा भुक्तानीमा छुट<br><br>वास्तविक दरहरू ऋण रकम र अवधि अनुसार फरक पर्न सक्छ।`;
    }

    // Branches
    if (lowerMessage.includes('शाखा') || lowerMessage.includes('branch') ||
        lowerMessage.includes('कार्यालय') || lowerMessage.includes('office')) {
        return `हाम्रा शाखा कार्यालयहरू:<br><br>🏢 <strong>मुख्य कार्यालय:</strong><br>लहान-८, सिराहा, मधेश प्रदेश<br>फोन: 033-563067<br><br>🏪 <strong>अन्य शाखाहरू:</strong><br>• भक्तपुर, ललितपुर<br>• काभ्रे, धुलिखेल<br>• पनौती क्षेत्र<br><br>📍 सबै शाखाहरूमा:<br>• ऋण आवेदन<br>• कागजात जाँच<br>• भुक्तानी सुविधा<br>• ग्राहक सेवा<br><br>नजिकको शाखाका लागि सम्पर्क गर्नुहोस्।`;
    }

    // Contact
    if (lowerMessage.includes('सम्पर्क') || lowerMessage.includes('contact') ||
        lowerMessage.includes('फोन') || lowerMessage.includes('phone') || lowerMessage.includes('number')) {
        return `हामीसँग सम्पर्क गर्नुहोस्:<br><br>📞 <strong>फोन:</strong> 033-563067<br>📧 <strong>इमेल:</strong> info@swastikmicrofinance.com<br>📍 <strong>ठेगाना:</strong> लहान-८, सिराहा<br><br>🕒 <strong>कार्य समय:</strong><br>• सोम-शुक्र: १०:०० AM - ५:०० PM<br>• शनिबार: ११:०० AM - २:०० PM<br>• बिदा: आइतबार<br><br>💬 <strong>तत्काल सहायता:</strong><br>हाम्रो वेबसाइट च्याट वा WhatsApp मार्फत पनि सहायता पाउन सक्नुहुन्छ।`;
    }

    // Application process
    if (lowerMessage.includes('आवेदन') || lowerMessage.includes('application') ||
        lowerMessage.includes('प्रक्रिया') || lowerMessage.includes('process') || lowerMessage.includes('apply')) {
        return `ऋण आवेदन प्रक्रिया:<br><br>📝 <strong>चरण १:</strong> आवश्यक कागजात तयार गर्नुहोस्<br>🏢 <strong>चरण २:</strong> नजिकको शाखा कार्यालयमा जानुहोस्<br>📋 <strong>चरण ३:</strong> आवेदन फारम भर्नुहोस्<br>🔍 <strong>चरण ४:</strong> कागजातहरूको जाँच<br>✅ <strong>चरण ५:</strong> स्वीकृति र भुक्तानी<br><br>⏱️ <strong>समय:</strong> सामान्यतया २ घण्टा<br>📞 <strong>सहायता:</strong> 033-563067<br><br><a href="contact.html" style="color:#1e3c72;">अब आवेदन गर्नुहोस् →</a>`;
    }

    // Calculator
    if (lowerMessage.includes('क्यालकुलेटर') || lowerMessage.includes('calculator') ||
        lowerMessage.includes('हिसाब') || lowerMessage.includes('calculate') || lowerMessage.includes('emi')) {
        return `ऋण EMI क्यालकुलेटर प्रयोग गर्नुहोस्:<br><br>🧮 <strong>क्यालकुलेटरमा उपलब्ध:</strong><br>• मासिक किस्ता हिसाब<br>• कुल ब्याज रकम<br>• कुल भुक्तानी<br>• विभिन्न ऋण प्रकारहरू<br><br><a href="index.html#calculator" style="color:#1e3c72;">क्यालकुलेटर खोल्नुहोस् →</a><br><br>वा आफ्नो ऋण रकम, ब्याजदर र अवधि बताउनुहोस्, म हिसाब गरिदिन्छु!`;
    }

    // Services
    if (lowerMessage.includes('सेवा') || lowerMessage.includes('service')) {
        return `हाम्रा मुख्य सेवाहरू:<br><br>💼 <strong>व्यापारिक ऋण:</strong> व्यवसाय विस्तारका लागि<br>🌾 <strong>कृषि ऋण:</strong> किसानहरूका लागि विशेष<br>🏠 <strong>घरायसी ऋण:</strong> घर निर्माण/मर्मत<br>👥 <strong>महिला समूह ऋण:</strong> महिला उद्यमीहरूका लागि<br>📚 <strong>शिक्षा ऋण:</strong> विद्यार्थीहरूका लागि<br>🚗 <strong>दुई पाङ्ग्रे ऋण:</strong> मोटरसाइकल खरिद<br><br>विस्तृत जानकारीका लागि <a href="Service.html" style="color:#1e3c72;">सेवा पृष्ठ हेर्नुहोस् →</a>`;
    }

    // About
    if (lowerMessage.includes('बारेमा') || lowerMessage.includes('about') ||
        lowerMessage.includes('कम्पनी') || lowerMessage.includes('company')) {
        return `स्वस्तिक लघुवित्त बित्तीय संस्था लिमिटेड:<br><br>🏢 <strong>स्थापना:</strong> २०७५ चैत्र १२<br>📊 <strong>अधिकृत पुँजी:</strong> ५ करोड ७७ लाख<br>👥 <strong>सन्तुष्ट ग्राहक:</strong> ७५,०००+<br>🏪 <strong>शाखा कार्यालय:</strong> १५ वटा<br><br>🎯 <strong>हाम्रो लक्ष्य:</strong><br>गरिबी निवारण र आर्थिक समावेशीकरण<br><br>विस्तृत जानकारीका लागि <a href="about.html" style="color:#1e3c72;">हाम्रो बारेमा पृष्ठ हेर्नुहोस् →</a>`;
    }

    // Career/Jobs
    if (lowerMessage.includes('करियर') || lowerMessage.includes('career') || 
        lowerMessage.includes('नोकरी') || lowerMessage.includes('job') || 
        lowerMessage.includes('रोजगार') || lowerMessage.includes('vacancy')) {
        return `हाम्रो कम्पनीमा काम गर्न चाहनुहुन्छ?<br><br>🎯 <strong>हामी भित्र्याउने:</strong><br>• अनुभवी र नयाँ प्रतिभाहरू<br>• ग्राहक सेवा टिम<br>• शाखा प्रबन्धक<br>• कर्पोरेट स्टाफ<br><br>📋 <strong>आवश्यक योग्यता:</strong><br>• न्यूनतम स्नातक<br>• communication skills<br>• teamwork<br><br><a href="career.html" style="color:#1e3c72;">हालका vacancies हेर्नुहोस् →</a><br><br>वा CV पठाउनुहोस्: info@swastikmicrofinance.com`;
    }

    // News/Updates
    if (lowerMessage.includes('समाचार') || lowerMessage.includes('news') || 
        lowerMessage.includes('अपडेट') || lowerMessage.includes('update')) {
        return `हालका समाचारहरू:<br><br>🏆 <strong>वर्ष २०८२ को उत्कृष्ट लघुवित्त पुरस्कार</strong><br>स्वस्तिक लघुवित्तले नेपाल लघुवित्त संघबाट उत्कृष्ट सेवा प्रदायकको पुरस्कार प्राप्त गरेको छ।<br><br>🌱 <strong>नयाँ कृषि ऋण कार्यक्रम</strong><br>किसानहरूका लागि विशेष मौसमी ऋण कार्यक्रम सुरु भएको छ।<br><br><a href="news.html" style="color:#1e3c72;">सबै समाचार हेर्नुहोस् →</a>`;
    }

    // Thanks
    if (lowerMessage.includes('धन्यवाद') || lowerMessage.includes('thanks') ||
        lowerMessage.includes('thank you') || lowerMessage.includes('dhanyabad')) {
        return `🙏 धन्यवाद!<br><br>तपाईंको विश्वासका लागि हामी आभारी छौं।<br><br>अरू कुनै सहायता चाहिएमा कृपया सोध्नुहोस्।<br><br>स्वस्तिक लघुवित्त - तपाईंको आर्थिक साझेदार! 💼`;
    }

    // Goodbye
    if (lowerMessage.includes('bye') || lowerMessage.includes('विदा') || 
        lowerMessage.includes('再见') || lowerMessage.includes(' farewell')) {
        return `👋 धन्यवाद र विदा!<br><br>म आजको लागि यति नै।<br>फेरि कुनै प्रश्न भएमा सोध्नुहोस्।<br><br>शुभ दिन! 😊`;
    }

    // Default response
    return `माफ गर्नुहोस्, तपाईंको प्रश्न बुझिन। 😅<br><br>कृपया यी विकल्पहरूबाट रोज्नुहोस्:<br>• ऋणका बारेमा जानकारी<br>• ब्याजदरहरू<br>• आवेदन प्रक्रिया<br>• शाखा कार्यालयहरू<br>• सम्पर्क जानकारी<br>• करियर अवसरहरू<br><br>वा <strong>033-563067</strong> मा फोन गर्नुहोस्।<br><br>म तपाईंलाई सहायता गर्न तयार छु! 💼`;
}

function toggleVoiceInput(force = null) {
    const voiceBtn = document.getElementById('aiVoiceBtn');

    if (force !== null) {
        aiChatbotState.isListening = force;
    } else {
        aiChatbotState.isListening = !aiChatbotState.isListening;
    }

    if (aiChatbotState.isListening) {
        if (voiceBtn) voiceBtn.classList.add('listening');
        if (aiChatbotState.recognition) {
            try {
                aiChatbotState.recognition.start();
            } catch (e) {
                console.log('Speech recognition already started');
            }
        }
    } else {
        if (voiceBtn) voiceBtn.classList.remove('listening');
        if (aiChatbotState.recognition) {
            try {
                aiChatbotState.recognition.stop();
            } catch (e) {
                console.log('Speech recognition already stopped');
            }
        }
    }
}

function saveChatHistory() {
    try {
        localStorage.setItem('aiChatbotHistory', JSON.stringify(aiChatbotState.messages));
    } catch (e) {
        console.warn('Could not save chat history');
    }
}

function loadChatHistory() {
    try {
        const history = localStorage.getItem('aiChatbotHistory');
        if (history) {
            aiChatbotState.messages = JSON.parse(history);
            // Load last 10 messages
            const recentMessages = aiChatbotState.messages.slice(-10);
            const messagesDiv = document.getElementById('aiChatbotMessages');

            if (messagesDiv) {
                recentMessages.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `ai-chatbot-message ai-chatbot-message-${msg.type}`;

                    const avatar = msg.type === 'bot' ? '🤖' : '👤';

                    messageDiv.innerHTML = `
                        <div class="ai-chatbot-message-avatar">${avatar}</div>
                        <div class="ai-chatbot-message-content">
                            <div class="ai-chatbot-message-bubble">
                                ${msg.content}
                            </div>
                            <span class="ai-chatbot-message-time">${msg.time}</span>
                        </div>
                    `;

                    messagesDiv.appendChild(messageDiv);
                });

                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        }
    } catch (e) {
        console.warn('Could not load chat history');
    }
}
