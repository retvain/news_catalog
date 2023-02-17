<template>
    <div class="modal-container" @keydown.esc.stop="$emit('modal-close-btn-clicked')">
        <div class="modal-header">
            <v-btn icon @click="$emit('modal-close-btn-clicked')">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </div>

        <div class="modal-body">
            <p>Удалить?</p>
        </div>

        <div class="modal-footer">
            <v-btn text @click="$emit('modal-close-btn-clicked')">Нет</v-btn>
            <v-btn text @click="deleteRecord">Да</v-btn>
        </div>
    </div>
</template>

<script>

export default {
    name: 'NewsModalDelete',
    components: {},
    props: {
        id: {
            type: Number,
            required: true,
        },
        modalMaskClick: {
            type: Number,
        },
    },
    watch: {
        modalMaskClick: function (newVal) {
            if (newVal) {
                this.$emit('modal-close-btn-clicked')
            }
        },
    },
    data: function () {
        return {
        }
    },
    computed: {
        requestRoute: function () {
            return process.env.VUE_APP_HOST + '/api/news/' + this.id;
        },
    },
    methods: {
        deleteRecord() {
            this.axios.delete(
                this.requestRoute,
            ).then(response => {
                if (response.data?.success) {
                    this.$emit('modal-delete-btn-clicked');
                } else {
                    console.log(response?.data);
                }
            }).catch(error => {
                console.log(error);
            });
        },
    },
}

</script>

<style lang="scss">
.modal-container.news-modal-delete {
    width: 300px;

    .modal-body {
        height: unset;
    }
}
</style>
