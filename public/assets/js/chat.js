const input = document.querySelector("#send-message-input");
const listMessages = document.querySelector("#list-messages");
const unreadMessagesIcons = document.querySelectorAll('.message_id[data-id-message]');

if (input) {
    input.addEventListener("click", function(e) {
        const unreadMessageIds = [];

        // Récupérer les IDs des messages non lus
        unreadMessagesIcons.forEach(icon => {
            const messageId = icon.getAttribute('data-id-message');
            if (messageId) {
                unreadMessageIds.push(messageId); // Ajouter à l'array si présent
            }
        });

        // Afficher ou utiliser le tableau des IDs
        console.log(unreadMessageIds);
        if (unreadMessageIds.length > 0) {
            fetch('/mes-conversation/messages/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json' // Indiquer le type de contenu
                },
                body: JSON.stringify(unreadMessageIds) // Convertir en JSON
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la mise à jour des messages lus.');
                    }
                })
                .catch(error => alert('Erreur de lecture: ' + error.message));
        }
    });
}

const formMessage = document.querySelector("#form-submit-message");
if (formMessage) {
    formMessage.addEventListener("submit", function(e) {
        e.preventDefault();

        // Créer un FormData à partir du formulaire
        const formData = new FormData(formMessage);

        // Envoi de la requête AJAX
        fetch(formMessage.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Indiquer une requête AJAX
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de réseau lors de l\'envoi du message.');
                }
                return response.json(); // On suppose que la réponse est en JSON
            })
            .then(data => {
                // Vérifiez si la réponse contient le message
                if (data.success) {
                    // Créez un nouvel élément de message
                    const newMessage = document.createElement('li');
                    newMessage.classList.add('media', 'sent', 'd-flex');
                    newMessage.innerHTML = `
                    <div class="media-body flex-grow-1">
                        <div class="msg-box">
                            <div>
                                <p>${data.message.content.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</p> <!-- Échapper les balises HTML -->
                            </div>
                            <span class="drop-item message-more-option">
                                <a href="#" class="more-vertical-bar" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="feather-more-vertical"></i>
                                </a>
                                <small class="dropdown-menu">
                                    <a class="dropdown-item" href="#"><i class="feather-copy me-2"></i>Copy</a>
                                    <a class="dropdown-item" href="#"><i class="feather-trash-2 me-2"></i>Delete</a>
                                </small>
                            </span>
                            <ul class="chat-msg-info name-date">
                                <li>
                                    <span class="chat-time">
                                        ${new Date(data.message.createdAt).toLocaleString('fr-FR')}
                                        ${data.message.readAt ?
                        `<i title="message vu le ${new Date(data.message.readAt).toLocaleString('fr-FR')}" class="fa-solid fa-check-double read"></i>` :
                        `<i class="fa-solid fa-check"></i>`}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                `;
                    listMessages.appendChild(newMessage); // Ajouter le nouveau message à la liste
                    formMessage.reset(); // Réinitialiser le formulaire
                } else {
                    alert("Erreur d'envoi : " + (data.error || "Erreur inconnue")); // Afficher l'erreur si présente
                }
            })
            .catch(error => {
                alert("Erreur serveur : " + error.message); // Afficher l'erreur du serveur
            });
    });
}