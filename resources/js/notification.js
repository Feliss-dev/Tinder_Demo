const userId = document.querySelector('meta[name="user-id"]').content;

window.Echo.channel('notifications.' + userId)
    .listen('.new-notification', (event) => {
        const bellIcon = document.getElementById('notification-bell');
        let unreadCount = parseInt(bellIcon.getAttribute('data-count')) || 0;

        unreadCount += 1;
        bellIcon.setAttribute('data-count', unreadCount);
        bellIcon.classList.remove('hidden'); // Hiển thị biểu tượng đếm
    });
