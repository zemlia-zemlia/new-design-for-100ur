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
        var FADE_TIME = 400; // ms
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
                if (window.role == 10) { // если юрист
                    log('Вам необходимо или подтвердить или отклонить запрос на консультацию');
                    var buttons = $('#buttons').html();
                    $('ul.messages').append('<li id="buttonsLi">' + buttons + '</li>');

                    $('#fileButton').attr('disabled', 'disabled');
                    $('#messageInput').attr('disabled', 'disabled');
                    $('#send').attr('disabled', 'disabled');
                    $('#closeButton').hide();
                    $('#accept').click(function () {
                        if (confirm('Принять чат?')) {
                            socket.emit('accept chat', {room: window.room, token: window.token});
                            $.get(
                                "/user/mailLawyerAccept",
                                {
                                    id: window.room
                                }
                            );

                            $('#buttonsLi').remove();
                            log('Ожидаем оплаты консультации от клиента ...');
                        }
                        return false;
                    });
                    $('#decline').click(function () {
                        if (confirm('Отклонить чат?')) {
                            socket.emit('decline chat', {room: window.room, token: window.token});
                            $.get(
                                "/user/mailLawyerDecline",
                                {
                                    id: window.room
                                }
                            );
                            $('#buttonsLi').remove();
                            log('Чат отклонен');
                        }
                        return false;

                    });
                } else { // если пользователь
                    log('Напишите свой вопрос. Когда юрист согласится на консультацию, вы сможете оплатить её.');
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
                    if (!window.waitForMoney) {
                        log('Ожидаем оплату ...');
                        window.waitForMoney = true;
                    }
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
                if (!window.showPayForm) {
                    $('ul.messages').append('<li>' + form + '</li>');
                    window.showPayForm = true;
                }
                log('Юрист принял ваш запрос', {prepend: 1});
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

                // console.log(data);
            });
            socket.on('new file', function (data) {
                log("<a target='_blank' href='" + data.file.link + "'>" + data.file.name + '</a>');
            });
            // SOCKET LISTENERS
            socket.on('new message', function (data) {
                if (!window.newMessages && data.token != window.token) {
                    log('<span class="newMessages">Новые сообщения</span>');
                    window.newMessages = true;
                }
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
// закоментил чтобы не надоедали
        // socket.on('disconnect', () => {
        //     log('Вы вышли из чата');
        // });
        //
        // socket.on('reconnect', () => {
        //     log('Вы перезашли в чат');
        //     window.location.reload();
        // });

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
                    // console.log(data);
                    socket.emit('new message', data);
                    if ($('.newMessages').length) {
                        $('.newMessages').closest('li').remove();
                    }
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

            $.get(
                "/user/mailUserNotification",
                {
                    id: window.room,
                    token: window.token
                }
            );
        }

        // Log a message
        const log = (message, options) => {
            var $el = $('<li>').addClass('log').html(message);
            addMessageElement($el, options);
        }

        // Adds the visual chat message to the message list
        const addChatMessage = (data, options) => {
            // console.log(data);
            // Don't fade the message in if there is an 'X was typing'
            var $typingMessages = getTypingMessages(data);
            options = options || {};
            // if ($typingMessages.length !== 0) {
            options.fade = true;
                $typingMessages.remove();
            // }
            var icon = '';
            if (data.token === window.token) {
                icon = (window.userCnt > 1) ? '<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>' : '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'
            }

            var username = (data.token === window.token) ? 'Вы ' : ' <img  style="width: 20px;" src="' + data.avatar + '"/> ' + data.username;
            var typingClass = (data.token === window.token) ? 'my' : '';
            // console.log([data.token, window.token, data.token === window.token, typingClass]);
            var mess = $($('#chatElem').html());
            $(mess).find('span.username').html(username);
            $(mess).find('span.dateMessage').html(data.date);
            $(mess).find('span.messageBody').html(data.message);
            $(mess).find('li.message').removeClass('my');
            $(mess).find('li.message').addClass(typingClass);
            addMessageElement($(mess), options);
        }

        // Adds the visual chat typing message
        const addChatTyping = (data) => {
            data.typing = true;
            if (data.token != window.token) {
                message = '<span class="username">' + data.username + '</span>' + ' печатает ...';
                $('#typing').html(message).fadeIn();
                // alert( data.username + 'печатает');
            }

        }

        // Removes the visual chat typing message
        const removeChatTyping = (data) => {
            $('#typing').fadeOut();
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
                if (!window.typeMessage) {
                    window.typeMessage = true;
                    socket.emit('typing', {room: window.room, username: window.username, token: window.token});
                }
                lastTypingTime = (new Date()).getTime();

                setTimeout(() => {
                    var typingTimer = (new Date()).getTime();
                    var timeDiff = typingTimer - lastTypingTime;
                    if (timeDiff >= TYPING_TIMER_LENGTH && window.typeMessage) {
                        socket.emit('stop typing', {room: window.room, username: window.username, token: window.token});
                        window.typeMessage = false;
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
                    socket.emit('stop typing', {room: window.room, username: window.username, token: window.token});
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
    var mime = 'image/png,image/jpeg,image/gif,image/bmp,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,image/*,application/pdf'.split(',');

    window.filesArray = [];
    const fileList = target.files;
    for (let index = 0; index < fileList.length; index++) {
        reader.onload = readerEvent => {

            // console.log([mime, fileList[index].type], mime.indexOf(fileList[index].type));
            if (mime.indexOf(fileList[index].type) !== -1) {
                $('#fileName').html('');
                const content = readerEvent.target.result.split('base64,')[1];
                $('#fileName').append('<span class="attachedFile"> Прикрепленные файлы: ' + fileList[index].name + '</span>&nbsp;');
                window.filesArray.push({
                    type: fileList[index].type,
                    size: fileList[index].size,
                    origin_name: fileList[index].name,
                    base64: content
                });
                // console.log(window.filesArray);
            }
        };
        reader.readAsDataURL(target.files[index]);
    }

}

