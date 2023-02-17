<template>
    <div>
        <h2>Тестовое задание</h2>
        <p>В рамках тестового задания реализован католог, который содержит два вида объектов:</p>
        <ul>
            <li>
                <router-link to="/news">Новость</router-link>
            </li>
            <li>
                <router-link to="/rubrics">Рубрика</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'TheHome',
    components: {},
    data: function () {
        return {
        }
    },
    methods: {
    },
}

</script>

<style  lang="scss">

</style>
