<template>
    <div>
        <div v-if="! loaded" class="card p-3 text-center">
            <loading-graphic  />
        </div>

        <div v-if="loaded">
            <div class="flex my-3">
                <h2>Staged</h2>
            </div>
            <gitamic-staged ref="staged" :data="staged"></gitamic-staged>

            <div class="flex my-3">
                <h2>Untracked</h2>
            </div>
            <gitamic-untracked ref="untracked" :data="untracked"></gitamic-untracked>
        </div>
    </div>
</template>

<script>
    export default {

        props: [
        ],

        data() {
            return {
                loaded: false,
                untracked: [],
                staged: [],
                meta: {},
            }
        },

        computed: {
        },

        watch: {
        },

        created() {
            this.rows = this.getStatus();

            this.$events.$on('composer-finished', this.getStatus);
        },

        methods: {
            getStatus() {
                this.$axios.get(cp_url(`gitamic/api/status`))
                    .then(response => {
                        this.loaded = true;
                        this.untracked = response.data.untracked;
                        this.staged = response.data.staged;
                        this.meta = response.data.meta;
                    });
            },
        }
    }
</script>
