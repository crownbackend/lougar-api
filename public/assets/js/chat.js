const input = document.querySelector("#send-message-input");
const listMessages = document.querySelector("#list-messages");
const unreadMessagesIcons = document.querySelectorAll('.message_id[data-id-message]');

input.addEventListener("click", function(e) {
    const unreadMessageIds = [];

    unreadMessagesIcons.forEach(icon => {
        const messageId = icon.getAttribute('data-id-message');
    });
    console.log(unreadMessageIds)
// Afficher ou utiliser le tableau des IDs
    if(unreadMessageIds.length > 0) {
        fetch('/mes-conversation/messages/read', {
            method: 'POST',
            body: JSON.stringify(unreadMessageIds)
        })
        .then(response => response.json())
        .then(() => {})
        .catch(error => alert('erreur de lecture'));
    }
});
