<template>
    <div class="chatbox">
        <div v-if="chat.isPrivateChatExpand" ref="sliderItem" id="ttt">
            <div class="chat-header clearfix">
                <div class="avatar">
                    <img v-if="chat.selectedReceiver.id" v-bind:src="chat.selectedReceiver.avatar" alt="avatar" />
                    <img v-else src="/images/chatbot-icon.png" alt="avatar" width="50px" />
                    <span class="online_icon offline"></span>
                </div>
                <div class="chat-about">
                    <div class="chat-with" v-if="chat.selectedReceiver.id">{{chat.selectedReceiver.lastname}} {{chat.selectedReceiver.firstname}}</div>
                    <div class="chat-with" v-else>Chat bot</div>
                    <div class="chat-num-messages">{{chat.selectedReceiver.email}}</div>
                </div>
<!--                <a class="close" ><i class="fas fa-times"></i></a>-->
            </div> <!-- end chat-header -->
            <div class="chat-history" id="chat-history">
                <div class="loading mb-2 text-center" v-if="chat.message.isLoading">
                    <svg
                        version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                      <path fill="#FF6700" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z" transform="rotate(18.3216 25 25)">
                        <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"></animateTransform>
                      </path>
                    </svg>
                </div>
                <MessageItem v-for="(message,i)  in chat.message.list"
                             :key="message.id" :ix="i"
                             :messages="chat.message.list"
                             :message="message"
                             :receiver="chat.selectedReceiver.id"
                ></MessageItem>
                <SuggestItem :suggests="chat.suggests"  >
                </SuggestItem>
            </div> <!-- end chat-history -->
            <div class="chat-message clearfix">
                <textarea name="message-to-send" v-model="inputMessage" id="message-to-send" @keyup.enter="saveMessage" placeholder="Viết tin nhắn..." class="input-message"></textarea>
                <a class=" send-user-chat" @click="saveMessage"><i class="zmdi zmdi-mail-send"></i></a>
            </div> <!-- end chat-message -->
        </div>
        <div v-else>
            <p class="d-flex align-items-center justify-content-center welcome">Xin chào</p>
        </div>
    </div>
</template>

<script>
    import MessageItem from './MessageItem'
    import SuggestItem from "./SuggestItem";
    export default {
        props: {
            chat:{
                type:Object,
                require:true
            }

        },
        components: {
            MessageItem,
            SuggestItem
        },
        data() {
            return {
                inputMessage: '',
                created_at: '',
            }
        },
        mounted() {
            this.$emit('openChatUser')
            $("#chat-history").on('scroll', async () => {
                const scroll = $("#chat-history").scrollTop()
                if (scroll < 1 && this.chat.message.currentPage < this.chat.message.lastPage) {
                    this.$emit('getMessages', this.chat.roomId, this.chat.message.currentPage + 1, true)
                }
            })
        },
        created () {
            // this.loadMessage()
            // Echo.channel('laravel_database_chatroom').listen('MessagePosted', (data)=>{
            //     let message = data.message
            //     message.user = data.user
            //     this.list_messages.push(message)
            // })
        },
        methods: {
            saveMessage () {
                // if (this.chat.selectedReceiver==0)
                //     this.$emit('saveMessage', this.inputMessage, this.chat.selectedReceiver)
                // else
                    this.$emit('saveMessage', this.inputMessage, this.chat.selectedReceiver.id)
                this.inputMessage = ''
            },
            CloseRoom(){

            }
        }
    }
</script>

