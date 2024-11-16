<script setup>
import { ref, onMounted } from 'vue'
import axios from "axios"

const boxFileId = ref('1701338644177')

const generateToken = async () => {
    const response = await axios.get('/get-new-box-api-token')
    return response?.data || null
}

const displayMagazinePreview = (token) => {
    const preview = new Box.Preview()

    preview.show(boxFileId.value, token, {
        container: ".preview",
        showDownload: true,
        contentAnswersProps: {
            show    : true,
        },
        hasHeader: true,
    })
}

onMounted(async () => {
    if (boxFileId.value) {
        const token = await generateToken()
        if (token) {
            displayMagazinePreview(token)
        }
    }
})
</script>

<template>
    <div class="preview"></div>
</template>

<style scoped>
.preview {
    width: 95%;
    height: 600px;
    background-color: #4a5568;
}
</style>
