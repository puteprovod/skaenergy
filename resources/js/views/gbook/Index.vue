<template>
    <div class="max-w-screen-xl flex mx-auto mb-10" ref="scrollComponent">
        <div class="items-center align-center md:px-5 md:basis-[75%] basis-full">
            <div v-if="infoMessage" v-html="infoMessage" id="infoMessage"
                 class="mt-5 px-7 py-4 shadow-sm text-sm text-ska-blue md:rounded-xl bg-blue-200 text-sm" role="alert">
            </div>
            <div v-if="errorMessage"
                 class="mt-5 px-7 py-4 shadow-sm text-sm text-red-800 md:rounded-xl bg-red-200 text-sm" role="alert">
                <span class="font-medium">{{ errorMessage }}</span>
            </div>
            <div v-if="user && !user.email_verified"
                 class="bg-white mt-5 px-7 py-4 shadow-sm font-semibold font-gray-500 md:rounded-xl flex flex-wrap flex-grow">
                <div class="text-gray-500 text-sm tracking-tight my-auto">
                    Введите код, отправленный вам на email: <span class="text-ska-blue font-bold">{{
                        user.email
                    }}</span>:
                </div>
                <div class="flex-nowrap mt-3 my-auto lg:ml-auto lg:mt-0 ml-2">
                    <input type="text" id="code" v-model="code"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg inline-flex focus:ring-blue-500 focus:border-blue-500 block w-24 p-2.5 mr-2 flex-nowrap"
                           placeholder="XXXXХХ">
                    <input type="submit" @click.prevent="tryToSendCode" value="Отправить"
                           class="bg-ska-blue rounded-lg hover:bg-[#274D82] inline-flex items-center font-medium text-sm text-white p-2.5 px-3 cursor-pointer flex-nowrap">
                    <input type="submit" @click.prevent="tryToRequestNewCode" value="&#8635;"
                           class="bg-ska-blue ml-1 rounded-lg hover:bg-[#274D82] inline-flex items-center font-medium text-sm text-white p-2.5 px-3 cursor-pointer flex-nowrap">

                </div>
            </div>
            <div v-if="mode==='userPosts'"
                 class="bg-white mt-5 p-7 shadow-sm font-semibold font-gray-500 md:rounded-xl flex">
                <div class="inline-block text-ska-blue text-base my-auto">
                    Все посты пользователя {{ user.nick }}, найдено {{ user.post_count }} шт.
                </div>
                <div class="ml-auto">
                    <button type="button" @click.prevent="mode='index'; posts = []; getPosts();"
                            class="bg-gray-500 rounded-lg hover:bg-[#274D82] inline-flex items-center font-medium text-sm text-white p-2.5 px-5 cursor-pointer">
                        Назад в гостевую
                        <svg aria-hidden="true" class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div v-if="user && mode!=='userPosts' && user.email_verified"
                 class="text-sm bg-white mt-5 p-7 shadow-sm md:rounded-xl flex">
                <div class="w-full">
                    <div>
                    <textarea v-model="comment" @focusin="showPostButtons = true" @keyup="formNotEmpty"
                              @focusout="!showEmojiBox && !showCodeBox && !rulesOpened && !isFormNotEmpty ? showPostButtons = false : ''"
                              id="messageArea" :class="showPostButtons ? 'h-48' : 'h-20'"
                              rows="4"
                              class="block p-2.5 w-full text-base text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-ska-blue"
                              placeholder="Что скажете?"></textarea>
                    </div>
                    <div :class="showPostButtons ? 'block' : 'hidden'">
                        <div class="inline-block">
                            <input @click.prevent="tryToPost"
                                   :class="isFormNotEmpty ? 'bg-ska-blue font-medium cursor-pointer hover:bg-[#274D82]' : 'bg-gray-500'"
                                   type="submit" value="Отправить"
                                   class="rounded-lg text-sm text-white p-2.5 px-4 md:px-8 mt-3">
                        </div>
                        <div class="inline-block align-middle content-center">
                            <a href="#" @mousedown="rulesOpened = true"
                               class="text-gray-500 hover:text-ska-blue md:hidden font-medium ml-4 text-sm tracking-tight">Правила</a>
                            <a href="#" @mousedown="rulesOpened = true"
                               class="text-gray-500 hover:text-ska-blue hidden md:block font-medium ml-7 text-sm tracking-tight">Правила гостевой</a>
                        </div>
                        <div class="float-right inline-block pt-1.5">
                            <a href="#" @mousedown="showEmojiBox = !showEmojiBox; showPostButtons = true"
                               title="Смайлики"
                               class="text-4xl text-gray-500 hover:text-ska-blue">&#9786;</a>
                        </div>
                        <div class="float-right inline-block mt-1.5 mr-2 pt-1.5 hidden md:block">
                            <a href="#" @mousedown="showCodeBox = !showCodeBox; showPostButtons = true"
                               title="Коды"
                               class="text-2xl text-gray-500 hover:text-ska-blue">[b]</a>
                        </div>
                    </div>
                </div>
                <div class="w-40 ml-5 text-2xl flex-none" :class="showCodeBox ? 'block' : 'hidden'">
                    <CodeBox/>
                </div>
                <div class="w-40 ml-5 text-2xl flex-none" :class="showEmojiBox ? 'block' : 'hidden'">
                    <EmojiBox :key="componentKey"/>
                </div>
            </div>
            <div v-for="post in posts" class="text-sm bg-white mt-5 p-7 shadow-sm md:rounded-xl">
                <PostComponent :post="post"/>
            </div>
            <div v-if="loading" class="mx-auto w-12 h-12">
                <svg aria-hidden="true" class="w-10 h-10 mr-2 text-gray-200 animate-spin fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
            </div>
        </div>
        <div class="basis-[25%] hidden md:block md:pr-5 min-w-min">
            <LoginForm :showAllPosts="true" ref="loginForm"/>
        </div>
    </div>
    <Rules :trigger="rulesOpened" :key="rulesOpened"></Rules>
</template>

<script>
import LoginForm from "../components/LoginForm.vue";
import EmojiBox from "../components/EmojiBox.vue";
import PostComponent from "../components/PostComponent.vue";
import Rules from "../components/Rules.vue";
import CodeBox from "../components/CodeBox.vue";

export default {
    name: "index",
    mounted() {
        this.getPosts()
        this.getEmojiWindow()
        window.addEventListener("scroll", this.handleScroll)
        if (this.$route.query.changedPassword === 'true') {
            this.infoMessage = '<span class="font-medium font-semibold">Ваш пароль успешно изменен. Используйте новый пароль для входа.</span>'
            setTimeout(() => {
                this.infoMessage = ''
            }, 10 * 1000);
        }
        if (this.$route.query.infoMessageX) {
            this.infoMessage = '<span class="font-medium font-semibold">' + this.$route.query.infoMessageX + '</span>'
        }
    },
    unmounted() {
        window.removeEventListener("scroll", this.handleScroll)
    },

    created() {
        document.title = this.$route.meta.title;
    },
    data() {
        return {
            posts: [],
            comment: '',
            loading: false,
            emojiWindowSize: 35,
            emojiWindowPos: 0,
            showCodeBox: false,
            componentKey: 0,
            showEmojiBox: false,
            showPostButtons: false,
            isFormNotEmpty: false,
            errorMessage: '',
            infoMessage: '',
            mode: 'index',
            code: '',
            user: null,
            rulesOpened: false,
            emojis: ['😀', '😁', '😂', '🤣', '😃', '😄', '😅', '😆', '😉', '😊', '😋', '😎', '😍', '😘', '😗', '😙', '😚', '🙂', '🤗', '🤔', '😐', '😑', '😶', '🙄', '😏', '😣', '😥', '😮', '🤐', '😯', '😪', '😫', '😴', '😌', '🤓', '😛', '😜', '😝', '🤤', '😒', '😓', '😔', '😕', '🙃', '🤑', '😲', '🙁', '😖', '😞', '😟', '😤', '😢', '😭', '😦', '😧', '😨', '😩', '😬', '😰', '😱', '😳', '😵', '😡', '😠', '😇', '🤠', '🤡', '🤥', '😷', '🤒', '🤕', '🤢', '🤧', '😈', '👿', '👹', '👺', '💀', '☠', '👻', '👽', '👾', '🤖', '💩', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾', '🙈', '🙉', '🙊', '👦', '👦🏻', '👦🏼', '👦🏽', '👦🏾', '👦🏿', '👧', '👧🏻', '👧🏼', '👧🏽', '👧🏾', '👧🏿', '👨', '👨🏻', '👨🏼', '👨🏽', '👨🏾', '👨🏿', '👩', '👩🏻', '👩🏼', '👩🏽', '👩🏾', '👩🏿', '👴', '👴🏻', '👴🏼', '👴🏽', '👴🏾', '👴🏿', '👵', '👵🏻', '👵🏼', '👵🏽', '👵🏾', '👵🏿', '👶', '👶🏻', '👶🏼', '👶🏽', '👶🏾', '👶🏿', '👼', '👼🏻', '👼🏼', '👼🏽', '👼🏾', '👼🏿', '👮', '👮🏻', '👮🏼', '👮🏽', '👮🏾', '👮🏿', '🕵', '🕵🏻', '🕵🏼', '🕵🏽', '🕵🏾', '🕵🏿', '💂', '💂🏻', '💂🏼', '💂🏽', '💂🏾', '💂🏿', '👷', '👷🏻', '👷🏼', '👷🏽', '👷🏾', '👷🏿', '👳', '👳🏻', '👳🏼', '👳🏽', '👳🏾', '👳🏿', '👱', '👱🏻', '👱🏼', '👱🏽', '👱🏾', '👱🏿', '🎅', '🎅🏻', '🎅🏼', '🎅🏽', '🎅🏾', '🎅🏿', '🤶', '🤶🏻', '🤶🏼', '🤶🏽', '🤶🏾', '🤶🏿', '👸', '👸🏻', '👸🏼', '👸🏽', '👸🏾', '👸🏿', '🤴', '🤴🏻', '🤴🏼', '🤴🏽', '🤴🏾', '🤴🏿', '👰', '👰🏻', '👰🏼', '👰🏽', '👰🏾', '👰🏿', '🤵', '🤵🏻', '🤵🏼', '🤵🏽', '🤵🏾', '🤵🏿', '🤰', '🤰🏻', '🤰🏼', '🤰🏽', '🤰🏾', '🤰🏿', '👲', '👲🏻', '👲🏼', '👲🏽', '👲🏾', '👲🏿', '🙍', '🙍🏻', '🙍🏼', '🙍🏽', '🙍🏾', '🙍🏿', '🙎', '🙎🏻', '🙎🏼', '🙎🏽', '🙎🏾', '🙎🏿', '🙅', '🙅🏻', '🙅🏼', '🙅🏽', '🙅🏾', '🙅🏿', '🙆', '🙆🏻', '🙆🏼', '🙆🏽', '🙆🏾', '🙆🏿', '💁', '💁🏻', '💁🏼', '💁🏽', '💁🏾', '💁🏿', '🙋', '🙋🏻', '🙋🏼', '🙋🏽', '🙋🏾', '🙋🏿', '🙇', '🙇🏻', '🙇🏼', '🙇🏽', '🙇🏾', '🙇🏿', '🤦', '🤦🏻', '🤦🏼', '🤦🏽', '🤦🏾', '🤦🏿', '🤷', '🤷🏻', '🤷🏼', '🤷🏽', '🤷🏾', '🤷🏿', '💆', '💆🏻', '💆🏼', '💆🏽', '💆🏾', '💆🏿', '💇', '💇🏻', '💇🏼', '💇🏽', '💇🏾', '💇🏿', '🚶', '🚶🏻', '🚶🏼', '🚶🏽', '🚶🏾', '🚶🏿', '🏃', '🏃🏻', '🏃🏼', '🏃🏽', '🏃🏾', '🏃🏿', '💃', '💃🏻', '💃🏼', '💃🏽', '💃🏾', '💃🏿', '🕺', '🕺🏻', '🕺🏼', '🕺🏽', '🕺🏾', '🕺🏿', '👯', '🕴', '🗣', '👤', '👥', '🤺', '🏇', '⛷', '🏂', '🏌', '🏄', '🏄🏻', '🏄🏼', '🏄🏽', '🏄🏾', '🏄🏿', '🚣', '🚣🏻', '🚣🏼', '🚣🏽', '🚣🏾', '🚣🏿', '🏊', '🏊🏻', '🏊🏼', '🏊🏽', '🏊🏾', '🏊🏿', '⛹', '⛹🏻', '⛹🏼', '⛹🏽', '⛹🏾', '⛹🏿', '🏋', '🏋🏻', '🏋🏼', '🏋🏽', '🏋🏾', '🏋🏿', '🚴', '🚴🏻', '🚴🏼', '🚴🏽', '🚴🏾', '🚴🏿', '🚵', '🚵🏻', '🚵🏼', '🚵🏽', '🚵🏾', '🚵🏿', '🏎', '🏍', '🤸', '🤸🏻', '🤸🏼', '🤸🏽', '🤸🏾', '🤸🏿', '🤼', '🤼🏻', '🤼🏼', '🤼🏽', '🤼🏾', '🤼🏿', '🤽', '🤽🏻', '🤽🏼', '🤽🏽', '🤽🏾', '🤽🏿', '🤾', '🤾🏻', '🤾🏼', '🤾🏽', '🤾🏾', '🤾🏿', '🤹', '🤹🏻', '🤹🏼', '🤹🏽', '🤹🏾', '🤹🏿', '👫', '👬', '👭', '💏', '👩‍❤️‍💋‍👨', '👨‍❤️‍💋‍👨', '👩‍❤️‍💋‍👩', '💑', '👩‍❤️‍👨', '👨‍❤️‍👨', '👩‍❤️‍👩', '👪', '👨‍👩‍👦', '👨‍👩‍👧', '👨‍👩‍👧‍👦', '👨‍👩‍👦‍👦', '👨‍👩‍👧‍👧', '👨‍👨‍👦', '👨‍👨‍👧', '👨‍👨‍👧‍👦', '👨‍👨‍👦‍👦', '👨‍👨‍👧‍👧', '👩‍👩‍👦', '👩‍👩‍👧', '👩‍👩‍👧‍👦', '👩‍👩‍👦‍👦', '👩‍👩‍👧‍👧', '🏻', '🏼', '🏽', '🏾', '🏿', '💪', '💪🏻', '💪🏼', '💪🏽', '💪🏾', '💪🏿', '🤳', '🤳🏻', '🤳🏼', '🤳🏽', '🤳🏾', '🤳🏿', '👈', '👈🏻', '👈🏼', '👈🏽', '👈🏾', '👈🏿', '👉', '👉🏻', '👉🏼', '👉🏽', '👉🏾', '👉🏿', '☝', '☝🏻', '☝🏼', '☝🏽', '☝🏾', '☝🏿', '👆', '👆🏻', '👆🏼', '👆🏽', '👆🏾', '👆🏿', '🖕', '🖕🏻', '🖕🏼', '🖕🏽', '🖕🏾', '🖕🏿', '👇', '👇🏻', '👇🏼', '👇🏽', '👇🏾', '👇🏿', '✌', '✌🏻', '✌🏼', '✌🏽', '✌🏾', '✌🏿', '🤞', '🤞🏻', '🤞🏼', '🤞🏽', '🤞🏾', '🤞🏿', '🖖', '🖖🏻', '🖖🏼', '🖖🏽', '🖖🏾', '🖖🏿', '🤘', '🤘🏻', '🤘🏼', '🤘🏽', '🤘🏾', '🤘🏿', '🤙', '🤙🏻', '🤙🏼', '🤙🏽', '🤙🏾', '🤙🏿', '🖐', '🖐🏻', '🖐🏼', '🖐🏽', '🖐🏾', '🖐🏿', '✋', '✋🏻', '✋🏼', '✋🏽', '✋🏾', '✋🏿', '👌', '👌🏻', '👌🏼', '👌🏽', '👌🏾', '👌🏿', '👍', '👍🏻', '👍🏼', '👍🏽', '👍🏾', '👍🏿', '👎', '👎🏻', '👎🏼', '👎🏽', '👎🏾', '👎🏿', '✊', '✊🏻', '✊🏼', '✊🏽', '✊🏾', '✊🏿', '👊', '👊🏻', '👊🏼', '👊🏽', '👊🏾', '👊🏿', '🤛', '🤛🏻', '🤛🏼', '🤛🏽', '🤛🏾', '🤛🏿', '🤜', '🤜🏻', '🤜🏼', '🤜🏽', '🤜🏾', '🤜🏿', '🤚', '🤚🏻', '🤚🏼', '🤚🏽', '🤚🏾', '🤚🏿', '👋', '👋🏻', '👋🏼', '👋🏽', '👋🏾', '👋🏿', '👏', '👏🏻', '👏🏼', '👏🏽', '👏🏾', '👏🏿', '✍', '✍🏻', '✍🏼', '✍🏽', '✍🏾', '✍🏿', '👐', '👐🏻', '👐🏼', '👐🏽', '👐🏾', '👐🏿', '🙌', '🙌🏻', '🙌🏼', '🙌🏽', '🙌🏾', '🙌🏿', '🙏', '🙏🏻', '🙏🏼', '🙏🏽', '🙏🏾', '🙏🏿', '🤝', '🤝🏻', '🤝🏼', '🤝🏽', '🤝🏾', '🤝🏿', '💅', '💅🏻', '💅🏼', '💅🏽', '💅🏾', '💅🏿', '👂', '👂🏻', '👂🏼', '👂🏽', '👂🏾', '👂🏿', '👃', '👃🏻', '👃🏼', '👃🏽', '👃🏾', '👃🏿', '👣', '👀', '👁', '👁‍🗨', '👅', '👄', '💋', '💘', '❤', '💓', '💔', '💕', '💖', '💗', '💙', '💚', '💛', '💜', '🖤', '💝', '💞', '💟', '❣', '💌', '💤', '💢', '💣', '💥', '💦', '💨', '💫', '💬', '🗨', '🗯', '💭', '🕳', '👓', '🕶', '👔', '👕', '👖', '👗', '👘', '👙', '👚', '👛', '👜', '👝', '🛍', '🎒', '👞', '👟', '👠', '👡', '👢', '👑', '👒', '🎩', '🎓', '⛑', '📿', '💄', '💍', '💎', '🐵', '🐒', '🦍', '🐶', '🐕', '🐩', '🐺', '🦊', '🐱', '🐈', '🦁', '🐯', '🐅', '🐆', '🐴', '🐎', '🦌', '🦄', '🐮', '🐂', '🐃', '🐄', '🐷', '🐖', '🐗', '🐽', '🐏', '🐑', '🐐', '🐪', '🐫', '🐘', '🦏', '🐭', '🐁', '🐀', '🐹', '🐰', '🐇', '🐿', '🦇', '🐻', '🐨', '🐼', '🐾', '🦃', '🐔', '🐓', '🐣', '🐤', '🐥', '🐦', '🐧', '🕊', '🦅', '🦆', '🦉', '🐸', '🐊', '🐢', '🦎', '🐍', '🐲', '🐉', '🐳', '🐋', '🐬', '🐟', '🐠', '🐡', '🦈', '🐙', '🐚', '🦀', '🦐', '🦑', '🦋', '🐌', '🐛', '🐜', '🐝', '🐞', '🕷', '🕸', '🦂', '💐', '🌸', '💮', '🏵', '🌹', '🥀', '🌺', '🌻', '🌼', '🌷', '🌱', '🌲', '🌳', '🌴', '🌵', '🌾', '🌿', '☘', '🍀', '🍁', '🍂', '🍃', '🍇', '🍈', '🍉', '🍊', '🍋', '🍌', '🍍', '🍎', '🍏', '🍐', '🍑', '🍒', '🍓', '🥝', '🍅', '🥑', '🍆', '🥔', '🥕', '🌽', '🌶', '🥒', '🍄', '🥜', '🌰', '🍞', '🥐', '🥖', '🥞', '🧀', '🍖', '🍗', '🥓', '🍔', '🍟', '🍕', '🌭', '🌮', '🌯', '🥙', '🥚', '🍳', '🥘', '🍲', '🥗', '🍿', '🍱', '🍘', '🍙', '🍚', '🍛', '🍜', '🍝', '🍠', '🍢', '🍣', '🍤', '🍥', '🍡', '🍦', '🍧', '🍨', '🍩', '🍪', '🎂', '🍰', '🍫', '🍬', '🍭', '🍮', '🍯', '🍼', '🥛', '☕', '🍵', '🍶', '🍾', '🍷', '🍸', '🍹', '🍺', '🍻', '🥂', '🥃', '🍽', '🍴', '🥄', '🔪', '🏺', '🌍', '🌎', '🌏', '🌐', '🗺', '🗾', '🏔', '⛰', '🌋', '🗻', '🏕', '🏖', '🏜', '🏝', '🏞', '🏟', '🏛', '🏗', '🏘', '🏙', '🏚', '🏠', '🏡', '🏢', '🏣', '🏤', '🏥', '🏦', '🏨', '🏩', '🏪', '🏫', '🏬', '🏭', '🏯', '🏰', '💒', '🗼', '🗽', '⛪', '🕌', '🕍', '⛩', '🕋', '⛲', '⛺', '🌁', '🌃', '🌄', '🌅', '🌆', '🌇', '🌉', '♨', '🌌', '🎠', '🎡', '🎢', '💈', '🎪', '🎭', '🖼', '🎨', '🎰', '🚂', '🚃', '🚄', '🚅', '🚆', '🚇', '🚈', '🚉', '🚊', '🚝', '🚞', '🚋', '🚌', '🚍', '🚎', '🚐', '🚑', '🚒', '🚓', '🚔', '🚕', '🚖', '🚗', '🚘', '🚙', '🚚', '🚛', '🚜', '🚲', '🛴', '🛵', '🚏', '🛣', '🛤', '⛽', '🚨', '🚥', '🚦', '🚧', '🛑', '⚓', '⛵', '🛶', '🚤', '🛳', '⛴', '🛥', '🚢', '✈', '🛩', '🛫', '🛬', '💺', '🚁', '🚟', '🚠', '🚡', '🚀', '🛰', '🛎', '🚪', '🛌', '🛏', '🛋', '🚽', '🚿', '🛀', '🛀🏻', '🛀🏼', '🛀🏽', '🛀🏾', '🛀🏿', '🛁', '⌛', '⏳', '⌚', '⏰', '⏱', '⏲', '🕰', '🕛', '🕧', '🕐', '🕜', '🕑', '🕝', '🕒', '🕞', '🕓', '🕟', '🕔', '🕠', '🕕', '🕡', '🕖', '🕢', '🕗', '🕣', '🕘', '🕤', '🕙', '🕥', '🕚', '🕦', '🌑', '🌒', '🌓', '🌔', '🌕', '🌖', '🌗', '🌘', '🌙', '🌚', '🌛', '🌜', '🌡', '☀', '🌝', '🌞', '⭐', '🌟', '🌠', '☁', '⛅', '⛈', '🌤', '🌥', '🌦', '🌧', '🌨', '🌩', '🌪', '🌫', '🌬', '🌀', '🌈', '🌂', '☂', '☔', '⛱', '⚡', '❄', '☃', '⛄', '☄', '🔥', '💧', '🌊', '🎃', '🎄', '🎆', '🎇', '✨', '🎈', '🎉', '🎊', '🎋', '🎍', '🎎', '🎏', '🎐', '🎑', '🎀', '🎁', '🎗', '🎟', '🎫', '🎖', '🏆', '🏅', '🥇', '🥈', '🥉', '⚽', '⚾', '🏀', '🏐', '🏈', '🏉', '🎾', '🎱', '🎳', '🏏', '🏑', '🏒', '🏓', '🏸', '🥊', '🥋', '🥅', '🎯', '⛳', '⛸', '🎣', '🎽', '🎿', '🎮', '🕹', '🎲', '♠', '♥', '♦', '♣', '🃏', '🀄', '🎴', '🔇', '🔈', '🔉', '🔊', '📢', '📣', '📯', '🔔', '🔕', '🎼', '🎵', '🎶', '🎙', '🎚', '🎛', '🎤', '🎧', '📻', '🎷', '🎸', '🎹', '🎺', '🎻', '🥁', '📱', '📲', '☎', '📞', '📟', '📠', '🔋', '🔌', '💻', '🖥', '🖨', '⌨', '🖱', '🖲', '💽', '💾', '💿', '📀', '🎥', '🎞', '📽', '🎬', '📺', '📷', '📸', '📹', '📼', '🔍', '🔎', '🔬', '🔭', '📡', '🕯', '💡', '🔦', '🏮', '📔', '📕', '📖', '📗', '📘', '📙', '📚', '📓', '📒', '📃', '📜', '📄', '📰', '🗞', '📑', '🔖', '🏷', '💰', '💴', '💵', '💶', '💷', '💸', '💳', '💹', '💱', '💲', '✉', '📧', '📨', '📩', '📤', '📥', '📦', '📫', '📪', '📬', '📭', '📮', '🗳', '✏', '✒', '🖋', '🖊', '🖌', '🖍', '📝', '💼', '📁', '📂', '🗂', '📅', '📆', '🗒', '🗓', '📇', '📈', '📉', '📊', '📋', '📌', '📍', '📎', '🖇', '📏', '📐', '✂', '🗃', '🗄', '🗑', '🔒', '🔓', '🔏', '🔐', '🔑', '🗝', '🔨', '⛏', '⚒', '🛠', '🗡', '⚔', '🔫', '🏹', '🛡', '🔧', '🔩', '⚙', '🗜', '⚗', '⚖', '🔗', '⛓', '💉', '💊', '🚬', '⚰', '⚱', '🗿', '🛢', '🔮', '🛒', '🏧', '🚮', '🚰', '♿', '🚹', '🚺', '🚻', '🚼', '🚾', '🛂', '🛃', '🛄', '🛅', '⚠', '🚸', '⛔', '🚫', '🚳', '🚭', '🚯', '🚱', '🚷', '📵', '🔞', '🇦🇨', '🇦🇩', '🇦🇪', '🇦🇫', '🇦🇬', '🇦🇮', '🇦🇱', '🇦🇲', '🇦🇴', '🇦🇶', '🇦🇷', '🇦🇸', '🇦🇹', '🇦🇺', '🇦🇼', '🇦🇽', '🇦🇿', '🇧🇦', '🇧🇧', '🇧🇩', '🇧🇪', '🇧🇫', '🇧🇬', '🇧🇭', '🇧🇮', '🇧🇯', '🇧🇱', '🇧🇲', '🇧🇳', '🇧🇴', '🇧🇶', '🇧🇷', '🇧🇸', '🇧🇹', '🇧🇻', '🇧🇼', '🇧🇾', '🇧🇿', '🇨🇦', '🇨🇨', '🇨🇩', '🇨🇫', '🇨🇬', '🇨🇭', '🇨🇮', '🇨🇰', '🇨🇱', '🇨🇲', '🇨🇳', '🇨🇴', '🇨🇵', '🇨🇷', '🇨🇺', '🇨🇻', '🇨🇼', '🇨🇽', '🇨🇾', '🇨🇿', '🇩🇪', '🇩🇬', '🇩🇯', '🇩🇰', '🇩🇲', '🇩🇴', '🇩🇿', '🇪🇦', '🇪🇨', '🇪🇪', '🇪🇬', '🇪🇭', '🇪🇷', '🇪🇸', '🇪🇹', '🇪🇺', '🇫🇮', '🇫🇯', '🇫🇰', '🇫🇲', '🇫🇴', '🇫🇷', '🇬🇦', '🇬🇧', '🇬🇩', '🇬🇪', '🇬🇫', '🇬🇬', '🇬🇭', '🇬🇮', '🇬🇱', '🇬🇲', '🇬🇳', '🇬🇵', '🇬🇶', '🇬🇷', '🇬🇸', '🇬🇹', '🇬🇺', '🇬🇼', '🇬🇾', '🇭🇰', '🇭🇲', '🇭🇳', '🇭🇷', '🇭🇹', '🇭🇺', '🇮🇨', '🇮🇩', '🇮🇪', '🇮🇱', '🇮🇲', '🇮🇳', '🇮🇴', '🇮🇶', '🇮🇷', '🇮🇸', '🇮🇹', '🇯🇪', '🇯🇲', '🇯🇴', '🇯🇵', '🇰🇪', '🇰🇬', '🇰🇭', '🇰🇮', '🇰🇲', '🇰🇳', '🇰🇵', '🇰🇷', '🇰🇼', '🇰🇾', '🇰🇿', '🇱🇦', '🇱🇧', '🇱🇨', '🇱🇮', '🇱🇰', '🇱🇷', '🇱🇸', '🇱🇹', '🇱🇺', '🇱🇻', '🇱🇾', '🇲🇦', '🇲🇨', '🇲🇩', '🇲🇪', '🇲🇫', '🇲🇬', '🇲🇭', '🇲🇰', '🇲🇱', '🇲🇲', '🇲🇳', '🇲🇴', '🇲🇵', '🇲🇶', '🇲🇷', '🇲🇸', '🇲🇹', '🇲🇺', '🇲🇻', '🇲🇼', '🇲🇽', '🇲🇾', '🇲🇿', '🇳🇦', '🇳🇨', '🇳🇪', '🇳🇫', '🇳🇬', '🇳🇮', '🇳🇱', '🇳🇴', '🇳🇵', '🇳🇷', '🇳🇺', '🇳🇿', '🇴🇲', '🇵🇦', '🇵🇪', '🇵🇫', '🇵🇬', '🇵🇭', '🇵🇰', '🇵🇱', '🇵🇲', '🇵🇳', '🇵🇷', '🇵🇸', '🇵🇹', '🇵🇼', '🇵🇾', '🇶🇦', '🇷🇪', '🇷🇴', '🇷🇸', '🇷🇺', '🇷🇼', '🇸🇦', '🇸🇧', '🇸🇨', '🇸🇩', '🇸🇪', '🇸🇬', '🇸🇭', '🇸🇮', '🇸🇯', '🇸🇰', '🇸🇱', '🇸🇲', '🇸🇳', '🇸🇴', '🇸🇷', '🇸🇸', '🇸🇹', '🇸🇻', '🇸🇽', '🇸🇾', '🇸🇿', '🇹🇦', '🇹🇨', '🇹🇩', '🇹🇫', '🇹🇬', '🇹🇭', '🇹🇯', '🇹🇰', '🇹🇱', '🇹🇲', '🇹🇳', '🇹🇴', '🇹🇷', '🇹🇹', '🇹🇻', '🇹🇼', '🇹🇿', '🇺🇦', '🇺🇬', '🇺🇲', '🇺🇸', '🇺🇾', '🇺🇿', '🇻🇦', '🇻🇨', '🇻🇪', '🇻🇬', '🇻🇮', '🇻🇳', '🇻🇺', '🇼🇫', '🇼🇸', '🇽🇰', '🇾🇪', '🇾🇹', '🇿🇦', '🇿🇲', '🇿🇼'],
        }
    },
    components: {
        CodeBox,
        Rules,
        PostComponent,
        EmojiBox,
        LoginForm
    },
    methods: {
        getPosts() {
            this.axios.get('/api/gbook/posts')
                .then(res => {
                    const posts = res.data.data
                    this.posts = this.preparePostComments(posts)
                })
        },
        deletePost(id){
            const index = this.posts.findIndex(n => n.id === id);
            if (index !== -1) {
                this.posts.splice(index, 1);
            }
            this.infoMessage = '<span class="font-medium">Сообщение успешно удалено!</span>'
            setTimeout(() => {
                this.infoMessage = ''
            }, 5 * 1000);
        },
        preparePostComments(comments) {
            comments.forEach(function (item) {
                item.comment = item.comment.replaceAll('<a ', '<a class="text-ska-blue font-semibold hover:underline break-all" ');
                item.comment = item.comment.replaceAll('<img ', '<img class="inline-block"');
            })
            return comments
        },
        getEmojiWindow() {
            this.emojiWindow = this.emojis.slice(this.emojiWindowPos, this.emojiWindowSize)
        },
        emojiWindowRight() {
            this.emojiWindowPos = this.emojiWindowPos + this.emojiWindowSize
            if (this.emojis.length <= this.emojiWindowPos)
                this.emojiWindowPos = 0
            this.componentKey += 1
        },
        emojiWindowLeft() {
            this.emojiWindowPos = this.emojiWindowPos - this.emojiWindowSize
            if (this.emojiWindowPos < 0)
                this.emojiWindowPos = this.emojis.length - (this.emojis.length % this.emojiWindowSize)
            this.componentKey += 1
        },
        formNotEmpty() {
            this.isFormNotEmpty = (document.getElementById('messageArea').value !== '')
        },
        addEmoji(emoji) {
            emoji = ' ' + emoji
            let el = document.getElementById('messageArea');
            let val = el.value, endIndex, range, doc = el.ownerDocument;
            if (typeof el.selectionStart == "number"
                && typeof el.selectionEnd == "number") {
                endIndex = el.selectionEnd;
                el.value = val.slice(0, endIndex) + emoji + val.slice(endIndex);
                el.selectionStart = el.selectionEnd = endIndex + emoji.length;
            } else if (doc.selection !== "undefined" && doc.selection.createRange) {
                el.focus();
                range = doc.selection.createRange();
                range.collapse(false);
                range.text = emoji;
                range.select();
            }
        },
        loadMorePosts() {
            this.loading = true
            const lastElement = this.posts.slice(-1)
            const url = (this.mode === 'userPosts') ? '/api/gbook/posts/' + this.user.id : '/api/gbook/posts/'
            this.axios.post(url, {
                'last': lastElement[0].id
            })
                .then(res => {
                    let newPosts = res.data.data
                    newPosts = this.preparePostComments(newPosts)
                    this.posts.push(...newPosts)
                    if (newPosts.length > 0) {
                        window.addEventListener("scroll", this.handleScroll)
                    } else {
                        window.removeEventListener("scroll", this.handleScroll)
                    }
                    this.loading = false
                })
        },
        handleScroll() {
            let element = this.$refs.scrollComponent
            if (element.getBoundingClientRect().bottom < window.innerHeight) {
                window.removeEventListener("scroll", this.handleScroll)
                this.loadMorePosts()
            }
        },
        successLogin() {
            this.infoMessage = '<span class="font-medium font-semibold">Успешный вход.</span>'
            setTimeout(() => {
                this.infoMessage = ''
            }, 3 * 1000);
        },
        userLogged(user) {
            this.user = user
        },
        userLoggedOut() {
            this.user = null
            this.infoMessage = '<span class="font-medium font-semibold">Вы успешно вышли из системы.</span>'
            setTimeout(() => {
                this.infoMessage = ''
            }, 3 * 1000);
        },
        getUserPosts(user) {
            this.posts = [];
            this.axios.get(`/api/gbook/posts/${user.id}`)
                .then(res => {
                    const posts = res.data.data
                    this.posts = this.preparePostComments(posts)
                    this.user = user
                    this.mode = 'userPosts'
                })
        },
        tryToSendCode() {
            if (!this.code) {
                this.errorMessage = 'Вы не указали код подтверждения';
                setTimeout(() => {
                    this.errorMessage = ''
                }, 5 * 1000);
                return;
            }
            this.axios.post(`/code_verify`, {
                code: this.code,
                email: this.user.email
            })
                .then(res => {
                    this.infoMessage = '<span class="font-medium font-semibold">Ваш email успешно активирован</span>'
                    setTimeout(() => {
                        this.infoMessage = ''
                    }, 5 * 1000);
                    this.user.email_verified = true;
                }).catch(error => {
                this.errorMessage = error.response.data.message
                setTimeout(() => {
                    this.errorMessage = ''
                }, 5 * 1000);
            })
        },
        tryToRequestNewCode() {
            this.axios.get(`/user/${this.user.id}/send_code`)
                .then(res => {
                    this.infoMessage = '<span class="font-medium">Код выслан повторно на ваш email</span> <span class="font-bold">serg-419@yandex.ru</span>'
                    setTimeout(() => {
                        this.infoMessage = ''
                    }, 5 * 1000);
                })
        },
        tryToPost() {
            this.axios.post(`/api/gbook/posts/create`, {
                comment: this.comment
            })
                .then(res => {
                    let newPost = []
                    newPost[0] = res.data.data
                    newPost = this.preparePostComments(newPost)
                    this.posts.unshift(newPost[0])
                    this.comment = ''
                    this.isFormNotEmpty = false;
                    this.showPostButtons = false;
                    this.showEmojiBox = false;
                    this.infoMessage = '<span class="font-medium">Ваше сообщение отправлено!</span>'
                    setTimeout(() => {
                        this.infoMessage = ''
                    }, 5 * 1000);
                    let child = this.$refs.loginForm;
                    child.user.post_count++;
                }).catch(
                error => {
                    this.errorMessage = error.response.data.message
                    setTimeout(() => {
                        this.errorMessage = ''
                    }, 5 * 1000);
                    console.log(error)
                }
            )
        },
        tryToReport(id){
            if (this.user) {
                this.axios.get(`/api/gbook/post/${id}/report`)
                    .then(res => {
                        this.infoMessage = '<span class="font-medium">Спасибо что сообщили! Модератор обратит внимание на этот пост.</span>'
                        setTimeout(() => {
                            this.infoMessage = ''
                        }, 5 * 1000);
                    })
                window.scrollTo(0,0)
            }else{
                this.infoMessage = '<span class="font-medium">Вы должны быть авторизованным пользователем с подтвержденным email, чтобы иметь возможность сообщить модератору о проблеме.</span>'
                setTimeout(() => {
                    this.infoMessage = ''
                }, 5 * 1000);
                window.scrollTo(0,0)
            }
        }
    }
}
</script>

<style scoped>

</style>
