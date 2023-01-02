<template>
    <div class="people-list" id="people-list">
        <div class="search">
            <input type="text" v-model="searchQuery" placeholder="Tìm nhân viên" class="search" />
            <i class="fa fa-search"></i>
        </div>
        <ul class="list">
            <li class="clearfix chatbot" @click="$emit('selectBot',{'id':0})">
                <img src="/images/chatbot-icon.png" width="64px">
                <div class="about">
                    <div class="name">Chat Bot</div>
                    <div class="status">
                        <i class="fa fa-circle online"></i> online
                    </div>
                </div>
            </li>

            <li v-for="user in filteredUsersList" :key="user.id" @click="$emit('selectReceiver',user)" class="clearfix">
                <img v-bind:src="user.avatar" alt="avatar" />
                <div class="about">
                    <div class="name">{{ user.username }} {{ user.id === $root.user ? '(Bạn)' : '' }}</div>
                    <div class="status">
                        <i class="fa fa-circle online"></i> online
                    </div>
                </div>
                <span class="badge badge-danger font-12px" v-if="user.new_messages">{{ user.new_messages }}</span>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
  props: {
    usersOnline: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      searchQuery: ''
    }
  },
  computed: {
    filteredUsersList () {
      return this.usersOnline.filter(row => row.username.toLowerCase().includes(this.searchQuery.toLowerCase()))
    }
  }
}
</script>

<style>

</style>
