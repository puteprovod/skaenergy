<template>
    <!-- The Modal -->
    <div id="myModalPersonal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="w-full">
                <span @click.prevent="closePersonal" class="close">&times;</span>
                <div class="text-center font-bold text-xl pt-5">
                    СОГЛАСИЕ НА ОБРАБОТКУ ПЕРСОНАЛЬНЫХ ДАННЫХ
                </div>
                <div class="p-5 text-justify">
                    Действуя свободно, своей волей и в своем интересе, а также подтверждая свою дееспособность,
                    физическое лицо дает свое согласие <span class="font-semibold">Сайту болельщиков СКА-Хабаровск SKAENERGY.RU</span> (Далее - Оператор) на обработку своих персональных данных со следующими условиями:
                    <ul class="list-decimal pl-5 mt-3">
                    <li>Данное Согласие дается на обработку персональных данных, как без использования средств
                        автоматизации, так и с их использованием</li>
                    <li>Согласие дается на обработку следующих моих персональных данных: имя, данные; доступные из профиля в социальной сети, с помощью которой происходит
                        авторизация на сайте/в приложении; адрес электронной почты; никнейм.</li>
                        <li>Цель обработки персональных данных: регистрация пользователя на сайте/в приложении.</li>
                    <li>В ходе обработки с персональными данными будут совершены следующие действия: сбор, систематизация;
                    хранение; использование; извлечение; блокирование; уничтожение; запись; удаление; накопление;
                        обновление; изменение.</li>
                    <li>Персональные данные обрабатываются до удаления пользователем личного кабинета на сайте/в приложении.
                    Согласие может быть отозвано субъектом персональных данных или его представителем путем направления
                    заявления по адресу электронной почты: admin@skaenergy.ru</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Personal",
    props: [
        'trigger'
    ],
    mounted() {
        if (this.trigger) {
            this.showPersonal()
        }
    },
    methods: {
        showPersonal() {
            document.getElementById("myModalPersonal").style.display = "block";
        },
        closePersonal() {
            document.getElementById("myModalPersonal").style.display = "none";
            this.$parent.personalOpened = false
        },
    }
}
</script>

<style scoped>

</style>
