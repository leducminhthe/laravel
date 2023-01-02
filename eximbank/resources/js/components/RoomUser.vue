<template>
    <div>
        <div class="fabs blue">
            <a id="prime" class="fab" @click="selectBot({'id':0})"
                ><i class="prime fa fa-comments"></i
            ></a>
        </div>
        <div id="chatuser">
            <div class="container clearfix">
                <!-- <ListUser :usersOnline="usersOnline" @selectReceiver="selectReceiver" @selectBot="selectBot"  /> -->
                <chat-user
                    :chat="privateChat"
                    @saveMessage="saveMessage"
                    @getMessages="getMessages"
                    @openChatUser="openChatUser"
                    @closeChat="closeChat"
                    @clickSuggest="clickSuggest"
                />
            </div>
        </div>
    </div>
</template>

<script>
import ListUser from "./ListUser";
import ChatUser from "./ChatUser";
import { parse } from "date-fns";
export default {
    components: {
        ListUser,
        ChatUser,
    },
    data() {
        return {
            usersUnreadMessage: [],
            usersOnline: [],
            currentRoom: {},
            publicChat: {},
            privateChat: {
                selectedReceiver: null,
                isPrivateChatExpand: false,
                isSelectedReceiverTyping: false,
                hasNewMessage: false,
                isSeen: null, // null: no new message, false: a message is waiting to be seen, true: user seen message (should display "Seen at..")
                seenAt: "",
                roomId: "",
                isOnline: true,
                message: {
                    isLoading: false,
                    list: [],
                    currentPage: 0,
                    perPage: 0,
                    total: 0,
                    lastPage: 0,
                    newMessageArrived: 0, // number of new messages we just got (use for saving scroll position)
                },
                suggests: [],
            },
        };
    },
    created() {
        this.$root.$on("clickSuggest", this.clickSuggest);
        /*Echo.join(`login`)
            .here((users) => {
                this.getUserRecent().then((res) => {
                    this.usersUnreadMessage = res.data;
                    this.usersUnreadMessage.forEach((el, i) => {
                        const index = users.findIndex(
                            (item) => item.id == el.id
                        );
                        if (index > -1) {
                            users.splice(index, 1);
                            users.push(el);
                        } else users.push(el);
                    });
                    this.usersOnline = users;
                });
            })
            .joining((user) => {
                const index = this.usersOnline.findIndex(
                    (item) => item.id === user.id
                );
                if (index < 0) this.usersOnline.push(user);
            }); 
        Echo.channel("logout").listen("Logout", (e) => {
            const index = this.usersOnline.findIndex(
                (item) => item.id === e.user_id
            );
            if (index > -1) {
                this.usersOnline.splice(index, 1);
            }
        });*/
       
        Echo.private(`room.${this.$root.user}`) // listen to user's own room (in order to receive all private messages from other users)
            .listen("MessagePost", (e) => {
                if (e.socketId != Echo.socketId()) {
                    const input = document.getElementById("message-to-send");
                    if (this.privateChat.isPrivateChatExpand && input.focus()) {
                        this.privateChat.message.list.push(e.message);
                        this.privateChat.isSeen = null; // when receive new private message, considered user have seen -> reset isSeen to inital state
                        this.privateChat.hasNewMessage = true; // notify user there's new message
                        this.scrollToBottom(
                            document.getElementById("chat-history"),
                            true
                        );
                        axios.put(
                            `/updateNotifyMessage/${this.$root.user}/${this.privateChat.roomId}`
                        );
                    } else {
                        // if private chat window doens't open, then we set the badge in ListUser

                        const index = this.usersOnline.findIndex(
                            (item) => item.id === e.message.sender.id
                        );
                        if (index > -1) {
                            this.usersOnline[index].new_messages++;
                        }
                    }
                }
            })
            .listen("MessageUser", (e) => {
                if (e.socketId != Echo.socketId()) {
                    this.privateChat.message.list.push(e.message);
                    this.privateChat.isSeen = null; // when receive new private message, considered user have seen -> reset isSeen to inital state
                    this.privateChat.hasNewMessage = true; // notify user there's new message
                    this.scrollToBottom(
                        document.getElementById("chat-history"),
                        true
                    );
                }
            });
        // this.getUserRecent();
    },
    destroyed() {
        this.$root.$off("clickSuggest", this.clickSuggest);
    },
    methods: {
        async getUserRecent() {
            return axios.get(`/messages/user/online-recent`);
            //.then(res=>{
            //    this.usersUnreadMessage = res.data
            //})
        },
        async getMessages(room, page = 1, loadMore = false) {
            const isPrivate = room.toString().includes("__");
            const chat = isPrivate ? this.privateChat : this.publicChat;
            try {
                chat.message.isLoading = true;
                const response = await axios.get(
                    `/messages/user/?room=${room}&page=${page}`
                );
                const suggestRes = await axios.get(`/messages/suggest/`);
                chat.message.list = [
                    ...response.data.data.reverse(),
                    ...chat.message.list,
                ];
                chat.suggests = [...suggestRes.data, ...chat.suggests];
                chat.message.currentPage = response.data.current_page;
                chat.message.perPage = response.data.per_page;
                chat.message.lastPage = response.data.last_page;
                chat.message.total = response.data.total;
                chat.message.newMessageArrived = response.data.data.length;
                axios.put(
                    `/user/updateNotifyMessage/${this.$root.user}/${this.privateChat.roomId}`
                );
                if (loadMore) {
                    this.$nextTick(() => {
                        const el = $("#chat-history");

                        // const el = $(isPrivate ? '#private_room' : '#shared_room')
                        const lastFirstMessage = el
                            .children()
                            .eq(chat.message.newMessageArrived - 1);
                        el.scrollTop(lastFirstMessage.position().top - 10);
                    });
                } else {
                    this.scrollToBottom(
                        document.getElementById("chat-history"),
                        false
                    );
                }
            } catch (error) {
                console.log(error);
            } finally {
                chat.message.isLoading = false;
            }
        },
        clickSuggest(id, text) {
            this.saveMessage(text, 0, id);
            // $('.suggest_context').children().remove()
            this.privateChat.suggests = [];

            // axios.post(`/messages/suggest/${id}`)
            // let newSuggest = this.getSuggestById(id)
            // this.chat.suggests = newSuggest
        },
        getSuggestById(id) {
            return axios.get(`/messages/suggest/${id}`).then((res) => {
                return res.data;
            });
        },
        async saveMessage(message, receiver, suggest = 0) {
            try {
                if (!message.trim().length) {
                    return;
                }

                // clean data before save to DB
                // message = sanitizeHtml(message).trim()

                if (message == null || message.trim() === "") {
                    return;
                }

                await axios
                    .post("/messages/user", {
                        receiver,
                        message,
                        suggest,
                    })
                    .then((response) => {
                        this.privateChat.message.list.push(
                            response.data.message
                        );
                        this.privateChat.isSeen = false; // waiting for other to seen this message
                        this.scrollToBottom(
                            document.getElementById("chat-history"),
                            true
                        );
                        if (this.privateChat.selectedReceiver.id == 0) {
                            axios
                                .post("/messages/bot", {
                                    message: message,
                                    suggest,
                                })
                                .catch(function (error) {
                                    console.log(error);
                                })
                                .then((botResponse) => {
                                    this.privateChat.message.list.push(
                                        botResponse.data.message
                                    );
                                    this.privateChat.suggests = [
                                        ...this.privateChat.suggests,
                                        ...botResponse.data.suggests,
                                    ];
                                    this.privateChat.isSeen = false; // waiting for other to seen this message
                                    this.scrollToBottom(
                                        document.getElementById("chat-history"),
                                        true
                                    );
                                });
                        }
                    });
                // Echo.private(`room.${this.privateChat.roomId}`)
                //   .whisper('typing', {
                //     user: this.$root.user,
                //     isTyping: false
                //   })
            } catch (error) {
                console.log(error);
            }
        },
        async selectReceiver(receiver) {
            if (this.$root.user == receiver.id) return;
            this.resetChat();
            setTimeout(function () {
                $("#profile p").addClass("animate");
                $("#profile").addClass("animate");
            }, 100);
            setTimeout(function () {
                $("#chat-messages").addClass("animate");
                $(".cx, .cy").addClass("s1");
                setTimeout(function () {
                    $(".cx, .cy").addClass("s2");
                }, 100);
                setTimeout(function () {
                    $(".cx, .cy").addClass("s3");
                }, 200);
            }, 150);
            $(".floatingImg").animate(
                {
                    width: "63px",
                    left: "42%",
                    top: "1px",
                },
                200
            );
            const roomId =
                this.$root.user > receiver.id
                    ? `${receiver.id}__${this.$root.user}`
                    : `${this.$root.user}__${receiver.id}`;
            this.privateChat.selectedReceiver = receiver;
            this.privateChat.isPrivateChatExpand = true;
            this.privateChat.roomId = roomId;
            await this.getMessages(roomId);
            this.focusPrivateInput();
        },
        async selectBot(receiver) {
            if (this.privateChat.isPrivateChatExpand) this.closeChat();
            else {
                setTimeout(function () {
                    $("#profile p").addClass("animate");
                    $("#profile").addClass("animate");
                }, 100);
                setTimeout(function () {
                    $("#chat-messages").addClass("animate");
                    $(".cx, .cy").addClass("s1");
                    setTimeout(function () {
                        $(".cx, .cy").addClass("s2");
                    }, 100);
                    setTimeout(function () {
                        $(".cx, .cy").addClass("s3");
                    }, 200);
                }, 150);
                $(".floatingImg").animate(
                    {
                        width: "63px",
                        left: "42%",
                        top: "1px",
                    },
                    200
                );
                const roomId = `0__${this.$root.user}`;
                this.privateChat.selectedReceiver = receiver;
                this.privateChat.isPrivateChatExpand = true;
                this.privateChat.roomId = roomId;
                await this.getMessages(roomId);
            }
        },
        focusPrivateInput() {
            const input = document.getElementById("message-to-send");
            if (input) {
                input.focus();
                this.privateChat.hasNewMessage = false;
                const index = this.usersOnline.findIndex(
                    (item) => item.id === this.privateChat.selectedReceiver.id
                );
                if (index > -1) {
                    this.usersOnline[index].new_messages = 0;
                }
            }
        },
        scrollToBottom(element, animate = true) {
            if (!element) {
                return;
            }
            this.$nextTick(() => {
                // run after Vue finishes updating the DOM
                if (animate) {
                    $(element).animate(
                        { scrollTop: element.scrollHeight },
                        { duration: "medium", easing: "swing" }
                    );
                } else {
                    $(element).scrollTop(element.scrollHeight);
                }
            });
        },
        openChatUser() {
            this.privateChat.hasNewMessage = false; // set this to false as now user is focusing the chat
            const index = this.usersOnline.findIndex(
                (item) => item.id === this.privateChat.selectedReceiver.id
            );
            if (index > -1) {
                this.usersOnline[index].new_messages = 0;
            }
        },
        resetChat() {
            // reset private chat when change to another user
            this.privateChat.message.list = [];
            this.privateChat.suggests = [];
            this.privateChat.selectedReceiver = null;
            this.privateChat.isPrivateChatExpand = false;
            this.privateChat.isSelectedReceiverTyping = false;
            this.privateChat.hasNewMessage = false;
            this.privateChat.isSeen = null; // null: no new message, false: a message is waiting to be seen, true: user seen message (should display "Seen at..")
            this.privateChat.seenAt = "";
            this.privateChat.roomId = "";
            this.privateChat.isOnline = true;
            console.log(33);
        },
        closeChat() {
            this.resetChat();
        },
    },
    computed: {
        totalUnreadPrivateMessages() {
            let count = 0;
            this.usersOnline.forEach((item) => {
                count += parseInt(item.new_messages);
            });
            return count;
        },
    },
    watch: {
        totalUnreadPrivateMessages() {
            if (this.totalUnreadPrivateMessages > 0) {
                document.title = `${
                    this.totalUnreadPrivateMessages > 0
                        ? "(" + this.totalUnreadPrivateMessages + ")"
                        : ""
                } tin nhắn`;
            } else {
                document.title = this.$root.appName;
            }
        },
    },
    mounted() {
        $(document).ready(function () {
            $("#prime, .chatbox .close").click(function () {
                $(".prime").toggleClass("fa-comments");
                $(".prime").toggleClass("fa-times");
                $("#chatuser").toggleClass("is-visible");
            });
        });
    },
};
</script>
