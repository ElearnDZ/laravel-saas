<template>
    <div id="user-menu" class="relative">
        <div @click="toggleMenu" :class="menuClass">
            <img class="rounded-sm" :src="gravatarUrl(user)">
            <span class="mx-4">{{ user.name }}</span>

            <svg v-show="!open" class="mr-2" width="12" viewBox="0 0 320 512"><path d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"/></svg>
            <svg v-show="open" class="mr-2" width="12" viewBox="0 0 320 512"><path d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"/></svg>
        </div>

        <div v-show="open" id="user-menu-dropdown" class="rounded-sm absolute mt-2 shadow w-full border border-grey-light">
            <a href="/logout" class="action action-danger block">Logout</a>
        </div>
    </div>
</template>

<script>
    import crypto from 'crypto'

    export default {
        props: ['user'],

        computed: {
            menuClass() {
                const activeState = this.open ? 'bg-grey-light' : ''

                return `transition-150 rounded-sm flex items-center hover:bg-grey-light p-2 cursor-pointer block ${activeState}`
            },
        },

        methods: {
            gravatarUrl(user) {
                const userEmail = user.email.trim().toLowerCase()
                const userHash = crypto.createHash('md5').update(userEmail).digest("hex");

                return `https://www.gravatar.com/avatar/${userHash}?s=32`
            },

            toggleMenu() {
                this.open = !this.open
            }
        },

        data() {
            return {
                open: false
            }
        }
    }
</script>
