<template>
    <div >
        <label v-if="ix>0 && messages[ix-1].updated_at != message.updated_at">{{formatDate(message.created_at)}}</label>
        <div class="clearfix" v-if="message.from === $root.user" >
            <div class="message right">
                <img v-bind:src="message.sender.avatar" />
                <div class="bubble">
                    {{ message.message}}
                    <span>{{ formatHourse(message.created_at)}}</span>
                </div>
            </div>
        </div>
        <div  class="clearfix" v-else>
            <div class="message">
                <img v-if="receiver" v-bind:src="`${message.sender.avatar}`" />
                <img v-else src="/images/chatbot-icon.png" />
                <div class="bubble">
                    <div v-if="receiver">{{message.message}}</div>
                    <div v-else v-html="message.message"></div>
                    <span>{{ formatHourse(message.created_at)}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {format} from 'date-fns'
    export default {
        props: {
            message:{
                type: Object,
                require: true
            },
            ix:{
                type: Number
            },
            messages:[],
            receiver:null,
        },
        methods:{
            formatHourse($date){
                return format(new Date($date),'HH:mm')
            },
            formatDate($date){
                return format(new Date($date),'EEEE, dd/MM/yyyy HH:mm')
            }
        }
    }
</script>

