<template>
    <div class="suggest_context">
        <div class="clearfix" >
            <ul class="message_suggest right">
                <li class="bubble_suggest"  v-for="suggest in suggests " :key="suggest.id" >
                    <a  @click="clickSuggest(suggest.id,suggest.name)">{{suggest.name}}</a>
                </li>
            </ul>
        </div>

    </div>
</template>

<script>
    export default {
        props:{
            suggest:{
                type:Object
            },
            suggests:[]
        },
        methods:{
            clickSuggest(id,name){ 
                this.$root.$emit('clickSuggest',id,name)

            }
        }

    }
</script>

