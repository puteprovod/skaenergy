<template>
    <div class="flex justify-center px-6 max-w-screen-xl py-8 mx-auto">
        <div class="bg-white rounded-2xl shadow md:mt-0 xl:p-0">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 w-full md:w-[21rem]">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                    Смена пароля
                </h1>
                <form class="space-y-4 md:space-y-4">
                    <input type="hidden" v-model="token">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Ваш
                            Email</label>
                        <input type="email" v-model="email" id="email"
                               class="bg-gray-200 border border-gray-300 text-gray-800 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               placeholder="name@pochta.ru" required disabled>
                        <label for="email" v-if="errorMessage.email"
                               class="block py-3 px-3 mt-2 text-red-800 rounded-lg bg-red-50 font-semibold text-sm">{{
                                errorMessage.email[0]
                            }}</label>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Новый пароль
                            <span
                                class="text-gray-500 text-xs">(не менее 6 символов)</span></label>
                        <input type="password" v-model="password" id="password" placeholder=""
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               required>
                        <label for="password" v-if="errorMessage.password" class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{ errorMessage.password[0] }}</label>
                    </div>
                    <div>
                        <label for="password_confirmation"
                               class="block mb-2 text-sm font-medium text-gray-900">Подтверждение
                            пароля</label>
                        <input type="password" v-model="password_confirmation" id="password_confirmation"
                               placeholder=""
                               class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                               required="">
                        <label for="password_confirmation" v-if="errorMessage.password_confirmation" class="block py-2 px-3 mt-2 text-red-800 rounded-lg bg-red-50 text-xs">{{ errorMessage.password_confirmation[0] }}</label>
                    </div>
                    <div v-if="infoMessage" v-html="infoMessage" id="infoMessage" class="mt-5 px-7 py-4 shadow-sm text-sm text-green-800 md:rounded-xl bg-green-200 text-sm" role="alert">
                    </div>
                    <div class="mb-3">
                        <input type="submit" @click.prevent="tryToSetNewPassword" value="Заменить пароль"
                               class="bg-ska-blue rounded-lg hover:bg-[#274D82] font-medium text-sm text-white p-2.5 w-full cursor-pointer">
                    </div>
                </form>
            </div>
        </div>
        <div class="pl-16 hidden lg:block flex-grow-0">
            <img :src="`/img/rollpics/${picNumber}.jpg`" class="object-scale-down shadow rounded-2xl">
        </div>
    </div>
</template>

<script>
export default {
    name: "Registration",
    mounted() {
        this.token = this.$route.params.token
        this.email = this.$route.query.email
        if (localStorage.getItem('x_xsrf_token')) {
            this.$router.push({name: 'index'})
        }
        this.picNumber = Math.ceil(Math.random() * 18);

    },
    created() {
        document.title = this.$route.meta.title;
    },
    data() {
        return {
            errorMessage: '',
            infoMessage: '',
            email: '',
            password: '',
            password_confirmation: '',
            picNumber: 0,
            token: '',
        }
    },
    methods: {
        tryToSetNewPassword() {
            this.errorMessage = Object;
            if (!this.email) this.errorMessage.email = {0: "Не указан email"}
            if (!this.password) this.errorMessage.password = {0: "Не заполнено поле"}
            if (!this.password_confirmation)  this.errorMessage.password_confirmation = {0: "Не заполнено поле"}
            if (Object.keys(this.errorMessage).length !== 0) return;
            this.axios.post('/reset-password', {
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
                token: this.token
            })
                .then(res => {
                    this.$router.push({name: 'index',query: { changedPassword: true }})
                }).catch(error => {
                    if (error.response.data.errors) {
                        this.errorMessage = error.response.data.errors
                    }
                    else {
                        this.errorMessage = error.response.data.message
                    }
                console.log(error)
            })
        }
    }
}
</script>

<style scoped>

</style>
