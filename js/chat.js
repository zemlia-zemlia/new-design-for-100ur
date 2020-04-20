$(function () {
    window.filesArray = [];
    window.userCnt = 0;
    init();

});

function init() {
    initChat();

    function initChat() {
        if (!window.room) {
            $('#chats').hide();
            return;
        }
        var FADE_TIME = 150; // ms
        var TYPING_TIMER_LENGTH = 400; // ms
        var COLORS = [
            '#e21400', '#91580f', '#f8a700', '#f78b00',
            '#58dc00', '#287b00', '#a8f07a', '#4ae8c4',
            '#3b88eb', '#3824aa', '#a700ff', '#d300e7'
        ];

        // Initialize variables
        var $window = $(window);
        var $usernameInput = $('.usernameInput'); // Input for username
        var $messages = $('.messages'); // Messages area
        var $inputMessage = $('.inputMessage'); // Input message input box

        var $loginPage = $('.login.page'); // The login page

        // Prompt for setting a username
        var connected = false;
        var typing = false;
        var lastTypingTime;
        var $currentInput = $usernameInput.focus();
        var socket = io(window.chaturl);
        $('#closeButton').click(function () {
            socket.emit('close chat', window.room);
        })
        // Socket events
        socket.on('connect', function () {

            console.log(window.room);
            socket.emit('join room', {room: window.room, token: window.token, role: window.role});
            connected = true;
            socket.emit('add user', {
                name: window.username,
                id: window.token,
                emp_id: "",
                room: window.room
            });
            socket.on('newChat', function () {
                if (window.newChat) {
                    return;
                }
                if (window.role == 10) {
                    log('Вам надо или подтвердить или отклонить чат');
                    var buttons = $('#buttons').html();
                    $('ul.messages').append('<li id="buttonsLi">' + buttons + '</li>');
                    $('#accept').click(function () {
                        if (confirm('Принять чат?')) {
                            socket.emit('accept chat', {room: window.room, token: window.token});
                            $('#buttonsLi').remove();
                            $('#fileButton').attr('disabled', 'disabled');
                            $('#messageInput').attr('disabled', 'disabled');
                            $('#send').attr('disabled', 'disabled');
                            $('#closeButton').hide();
                            log('Ожидаем оплаты от клиента ...');
                        }
                        return false;
                    });
                    $('#decline').click(function () {
                        if (confirm('Отклонить чат?')) {
                            socket.emit('decline chat', {room: window.room, token: window.token});
                            $('#buttonsLi').remove();
                            log('Чат отклонен');
                            $('#fileButton').attr('disabled', 'disabled');
                            $('#messageInput').attr('disabled', 'disabled');
                            $('#send').attr('disabled', 'disabled');
                            $('#closeButton').hide();
                        }
                        return false;

                    });
                } else {
                    log('Напишите свой вопрос юристу, после того, как он примет вопрос, вам будет предложена форма оплаты консультации');
                }
                window.newChat = 1;

            });
            /// Открытие чата после оплаты
            socket.on('openChat', function () {
                $('#fileButton').removeAttr('disabled');
                $('#messageInput').removeAttr('disabled');
                $('#send').removeAttr('disabled');
                if (window.role == 3) {
                    $('#closeButton').show();
                }
                if (!window.openChat) {
                    log('Чат открыт', {prepend: 1});
                    window.openChat = 1;
                }
            });
            // чат отменен
            socket.on('decline', function () {
                if (window.role == 3) {
                    log('К сожалению этот юрист не сможет вам помочь. Обратитесь к другому');
                }

                $('#fileButton').attr('disabled', 'disabled');
                $('#messageInput').attr('disabled', 'disabled');
                $('#send').attr('disabled', 'disabled');
                $('#closeButton').hide();
            });

            /// платежная форма
            socket.on('openPayForm', function (data) {
                if (window.role == 10) {
                    log('Ожидаем оплату ...')
                    $('#fileButton').attr('disabled', 'disabled');
                    $('#messageInput').attr('disabled', 'disabled');
                    $('#send').attr('disabled', 'disabled');
                    $('#closeButton').hide();
                    return;
                }
                $('#fileButton').attr('disabled', 'disabled');
                $('#messageInput').attr('disabled', 'disabled');
                $('#send').attr('disabled', 'disabled');
                $('#closeButton').hide();
                $('#js-summ').val(data.price);
                $('.js-summ').html(data.price);
                $('#formLabel').val('c-' + data.chatId);
                var form = $('#payForm').html();
                $('ul.messages').append('<li>' + form + '</li>');
                log('Требуется оплата', {prepend: 1});
            });
            // подтверждение оплаты
            socket.on('successpay', function () {
                log('Оплата подтверждена');
            });
            // закрытие чата
            socket.on('chatClosed', function () {
                log('Чат закрыт');
                $('#fileButton').attr('disabled', 'disabled');
                $('#messageInput').attr('disabled', 'disabled');
                $('#send').attr('disabled', 'disabled');
                $('#closeButton').hide();
            });
            socket.on('console', function (data) {
                console.log(data);
            });

            socket.on('user joined', function (data) {

                console.log(data);
            });
            socket.on('new file', function (data) {
                log("<a target='_blank' href='" + data.file.link + "'>" + data.file.name + '</a>');
            });
            // SOCKET LISTENERS
            socket.on('new message', function (data) {
                addChatMessage(data);
            });
        });
        // Whenever the server emits 'login', log the login message
        socket.on('login', (data) => {
            connected = true;
            // Display the welcome message
            var message = "Добро пожаловать в чат";
            log(message, {
                prepend: true
            });
            addParticipantsMessage(data);
        });
        // Whenever the server emits 'user joined', log it in the chat body
        socket.on('user joined', (data) => {

            // if (data.username) {
            //     log('Новый пользователь ' + ' присоединился к чату', {prepend: 1});
            //     addParticipantsMessage(data);
            // }
        });

        // Whenever the server emits 'user left', log it in the chat body
        socket.on('user left', (data) => {

            // log(' Пользователь покинул чат', {prepend: 1});
            // addParticipantsMessage(data);
            removeChatTyping(data);
        });

        // Whenever the server emits 'typing', show the typing message
        socket.on('typing', (data) => {
            addChatTyping(data);
        });

        // Whenever the server emits 'stop typing', kill the typing message
        socket.on('stop typing', (data) => {
            removeChatTyping(data);
        });

        socket.on('disconnect', () => {
            log('Вы вышли из чата');
        });

        socket.on('reconnect', () => {
            log('Вы перезашли в чат');

            socket.emit('add user', {
                    name: window.username,
                    id: window.token,
                    emp_id: "",
                    room: window.room
                }
            );
        });

        socket.on('reconnect_error', () => {
            log('attempt to reconnect has failed');
        });
        ///init chat

        const addParticipantsMessage = (data) => {
            var message = '';
            if (data.numUsers == 1) {
                window.userCnt = data.numUsers;
                // message += "сейчас в чате 1 человек";
            } else {
                if (data.numUsers !== undefined) {
                    window.userCnt = data.numUsers;
                    // message += "сейчас в чате " + data.numUsers + " человек";
                }

            }
            log(message, {prepend: 1});
        }
        $('#send').click(function () {
            sendMessage();
        });

        // Sends a chat message
        const sendMessage = () => {
            var message = $inputMessage.val();
            // Prevent markup from being injected into the message
            message = cleanInput(message);
            // if there is a non-empty message and a socket connection
            if ((message || window.filesArray.length) && connected) {
                $inputMessage.val('');
                // tell server to execute 'new message' and send along one parameter
                if (message) {
                    var data = {message: message, room: window.room, username: window.username, token: window.token};
                    console.log(data);
                    socket.emit('new message', data);
                }

                if (window.filesArray.length) {
                    socket.emit('new file', {
                        username: window.username,
                        token: window.token,
                        room: window.room,
                        files: window.filesArray,
                    });
                    window.filesArray = [];
                    $('#fileName').html('');
                }
            }
        }

        // Log a message
        const log = (message, options) => {
            var $el = $('<li>').addClass('log').html(message);
            addMessageElement($el, options);
        }

        // Adds the visual chat message to the message list
        const addChatMessage = (data, options) => {
            console.log(data);
            // Don't fade the message in if there is an 'X was typing'
            var $typingMessages = getTypingMessages(data);
            options = options || {};
            if ($typingMessages.length !== 0) {
                options.fade = false;
                $typingMessages.remove();
            }
            var icon = '';
            if (data.token === window.token) {
                icon = (window.userCnt > 1) ? '<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>' : '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'
            }

            var username = (data.token === window.token) ? 'Вы' : ' <img  style="width: 20px;" src="' + data.avatar + '"/> ' + data.username;
            var $usernameDiv = $('<span class="username"/>')
                .html(username)
                .css('color', getUsernameColor(window.username));
            var $messageBodyDiv = $('<span class="messageBody">')
                .html(data.message);

            var typingClass = (data.token === window.token) ? 'my' : '';
            var $messageDiv = $('<li class="message"/>')
                .data('username', data.username)
                .addClass(typingClass)
                .append($usernameDiv, icon, ' ', data.date + ' ', $messageBodyDiv);
            console.log($messageDiv);
            addMessageElement($messageDiv, options);
        }

        // Adds the visual chat typing message
        const addChatTyping = (data) => {
            // data.typing = true;
            // data.message = 'Печатает ...';
            // addChatMessage(data);
        }

        // Removes the visual chat typing message
        const removeChatTyping = (data) => {
            getTypingMessages(data).fadeOut(function () {
                $(this).remove();
            });
        }

        const addMessageElement = (el, options) => {
            var $el = $(el);

            // Setup default options
            if (!options) {
                options = {};
            }
            if (typeof options.fade === 'undefined') {
                options.fade = true;
            }
            if (typeof options.prepend === 'undefined') {
                options.prepend = false;
            }

            // Apply options
            if (options.fade) {
                $el.hide().fadeIn(FADE_TIME);
            }
            if (options.prepend) {
                $messages.prepend($el);
            } else {
                $messages.append($el);
            }
            $messages[0].scrollTop = $messages[0].scrollHeight;
        }

        // Prevents input from having injected markup
        const cleanInput = (input) => {
            return $('<div/>').text(input).html();
        }

        // Updates the typing event
        const updateTyping = () => {
            if (connected) {
                if (!typing) {
                    typing = true;
                    socket.emit('typing');
                }
                lastTypingTime = (new Date()).getTime();

                setTimeout(() => {
                    var typingTimer = (new Date()).getTime();
                    var timeDiff = typingTimer - lastTypingTime;
                    if (timeDiff >= TYPING_TIMER_LENGTH && typing) {
                        socket.emit('stop typing');
                        typing = false;
                    }
                }, TYPING_TIMER_LENGTH);
            }
        }

        // Gets the 'X is typing' messages of a user
        const getTypingMessages = (data) => {
            return $('.typing.message').filter(function (i) {
                return $(this).data('username') === data.username;
            });
        }

        // Gets the color of a username through our hash function
        const getUsernameColor = (username) => {
            // Compute hash code
            var hash = 7;
            for (var i = 0; i < username.length; i++) {
                hash = username.charCodeAt(i) + (hash << 5) - hash;
            }
            // Calculate color
            var index = Math.abs(hash % COLORS.length);
            return COLORS[index];
        }

        // Keyboard events

        $window.keydown(event => {
            // Auto-focus the current input when a key is typed
            if (!(event.ctrlKey || event.metaKey || event.altKey)) {
                $currentInput.focus();
            }
            // When the client hits ENTER on their keyboard
            if (event.which === 13) {
                if (window.username) {
                    sendMessage();
                    socket.emit('stop typing');
                    typing = false;
                } else {
                }
            }
        });

        $inputMessage.on('input', () => {
            updateTyping();
        });

        // Click events

        // Focus input when clicking anywhere on login page
        $loginPage.click(() => {
            $currentInput.focus();
        });

        // Focus input when clicking on the message input's border
        $inputMessage.click(() => {
            $inputMessage.focus();
        });


    }
}

function processWebImage(target) {
    var reader = new FileReader();
    window.filesArray = [];
    const fileList = target.files;
    for (let index = 0; index < fileList.length; index++) {
        reader.onload = readerEvent => {
            const content = readerEvent.target.result.split('base64,')[1];
            $('#fileName').append('<span>' + fileList[index].name + '</span>&nbsp;');
            window.filesArray.push({
                type: fileList[index].type,
                size: fileList[index].size,
                origin_name: fileList[index].name,
                base64: content
            });
            console.log(window.filesArray);
        };
        reader.readAsDataURL(target.files[index]);
    }

}

