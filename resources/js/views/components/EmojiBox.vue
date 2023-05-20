<template>
    <div class="text-center" id="emojis">
      <span v-for="emoji in emojiWindow" @click="emojiClicked(emoji)" class="mr-1.5 hover:cursor-pointer">
           {{ emoji }}
   </span>
    </div>
    <div class="text-sm mt-2 px-4">
        <div class="inline-block"><a href="#" @click="$parent.emojiWindowLeft">&#5130;</a></div>
        <div class="float-right inline-block"><a href="#" class="ml-auto" @click="$parent.emojiWindowRight">&#5125;</a>
        </div>
    </div>
</template>

<script>
export default {
    name: "emojiBox",
    data() {
        return {
            emojiWindow: [],
            emojiWindowSize: 30
        }
    },
    mounted() {
        this.getEmojiWindow()
    },
    methods: {
        getEmojiWindow() {
            this.emojiWindow = this.$parent.emojis.slice(this.$parent.emojiWindowPos, this.$parent.emojiWindowSize + this.$parent.emojiWindowPos)
        },
        emojiClicked(emoji) {
            this.$parent.addEmoji(emoji)
        }
    }
}
</script>

<style scoped>

</style>
