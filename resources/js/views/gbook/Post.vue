<template>
    <div class="max-w-screen-xl flex mx-auto mb-10" ref="scrollComponent">
        <div class="items-center align-center md:px-5 md:basis-[75%] basis-full">
            <div v-if="infoMessage" v-html="infoMessage" id="infoMessage" class="mt-5 px-7 py-4 shadow-sm text-sm text-ska-blue md:rounded-xl bg-blue-200 text-sm" role="alert">
            </div>
            <div v-if="errorMessage" class="mt-5 px-7 py-4 shadow-sm text-sm text-red-800 md:rounded-xl bg-red-200 text-sm" role="alert">
                <span class="font-medium">{{ errorMessage }}</span>
            </div>
            <div v-for="post in posts" class="text-sm bg-white mt-5 p-7 shadow-sm md:rounded-xl">
            <PostComponent :post="post"/>
        </div>
        </div>
        <div class="basis-[25%] hidden md:block md:pr-5 min-w-min">
            <LoginForm :showAllPosts="false" ref="loginForm"/>
        </div>
    </div>
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
        this.post_id = this.$route.params.id
        this.getPost()
    },
    created() {
        document.title = this.$route.meta.title;
    },
    data() {
        return {
            posts: [],
            post_id: 0,
            componentKey: 0,
            errorMessage: '',
            infoMessage: '',
            user: null,}
    },
    components: {
        PostComponent,
        LoginForm
    },
    methods: {
        getPost() {
            this.axios.get(`/api/gbook/post/${this.post_id}`)
                .then(res => {
                    const posts = [];
                      posts[0] = res.data.data
                    this.posts = this.preparePostComments(posts)
                })
        },
        preparePostComments(comments) {
            comments.forEach(function (item) {
                item.comment = item.comment.replaceAll('<a ', '<a class="text-ska-blue font-semibold hover:underline break-all" ');
                item.comment = item.comment.replaceAll('<img ', '<img class="inline-block"');
            })
            return comments
        },
        successLogin(){
            this.infoMessage = '<span class="font-medium font-semibold">Успешный вход.</span>'
            setTimeout(() => {this.infoMessage = '' },3 * 1000);
        },
        userLogged(user) {
            this.user = user
        },
        userLoggedOut() {
            this.user = null
            this.infoMessage = '<span class="font-medium font-semibold">Вы успешно вышли из системы.</span>'
            setTimeout(() => {this.infoMessage = '' },3 * 1000);
        }
    }
}
</script>

<style scoped>

</style>
