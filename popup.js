// NOTICE POPUP FUNCTIONS
function shouldShowPopup() {
    // Always show popup - no checkbox option
    return true;
}

function showNoticePopup() {
    console.log('Showing notice popup...');
    if (shouldShowPopup()) {
        const popup = document.getElementById('noticePopup');
        if (popup) {
            popup.style.display = 'flex';
            console.log('Popup display set to flex');
        } else {
            console.log('Popup element not found!');
        }
        
        // Set current date
        const dateElement = document.getElementById('currentDate');
        if (dateElement) {
            const now = new Date();
            const nepaliMonths = ['बैशाख', 'जेठ', 'असार', 'श्रावण', 'भदौ', 'असोज', 'कार्तिक', 'मंसिर', 'पौष', 'माघ', 'फाल्गुन', 'चैत्र'];
            const today = now.getDate() + ' ' + nepaliMonths[now.getMonth()] + ' ' + (now.getFullYear() + 2057);
            dateElement.textContent = today;
            console.log('Date set to: ' + today);
        }
    }
}

function closeNoticePopup() {
    const popup = document.getElementById('noticePopup');
    if (popup) popup.style.display = 'none';

    // Directly open full image
    openFullImage('notice1.jpg');
}

// PDF FUNCTION - Opens PDF in modal (like image)
function openPdfInModal() {
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');
    if (modal && pdfFrame) {
        pdfFrame.src = 'notice2.pdf';
        modal.classList.add('show');
    }
}

function openFullImage(imageSrc) {
    const modal = document.getElementById('fullImageModal');
    const img = document.getElementById('fullImage');
    if (modal && img) {
        img.src = imageSrc;
        modal.classList.add('show');
    }
}

function closeFullImage() {
    const modal = document.getElementById('fullImageModal');
    if (modal) modal.classList.remove('show');
    
    // Open PDF in modal after image is closed
    setTimeout(openPdfInModal, 300);
}

// Close PDF modal
function closePdfModal() {
    const modal = document.getElementById('pdfModal');
    const pdfFrame = document.getElementById('pdfFrame');
    if (modal) modal.classList.remove('show');
    if (pdfFrame) pdfFrame.src = '';
}

// Close popups when clicking outside
document.addEventListener('click', function(e) {
    const noticePopup = document.getElementById('noticePopup');
    
    if (noticePopup && noticePopup.style.display === 'flex') {
        if (e.target === noticePopup) {
            closeNoticePopup();
        }
    }
});

// Escape key to close popups
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeNoticePopup();
    }
});

window.addEventListener('load', function() {
    setTimeout(showNoticePopup, 500);
});

