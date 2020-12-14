<template>
    <div>
        <div class="flex mb-3">
            <h1 class="flex-1">{{ __('Gitamic') }}</h1>
            <button class="btn" @click.prevent="getStatus">{{ __('Refresh') }}</button>
            <button
                v-if="hasStagedChanges"
                class="ml-2 btn-primary flex items-center"
                @click="commit">
                <span>{{ __('Commit') }}</span>
            </button>
        </div>

        <div v-if="! loaded" class="card p-3 text-center">
            <loading-graphic  />
        </div>

        <div v-if="loaded">
            <div class="flex my-3">
                <h2>Staged</h2>
            </div>
            <gitamic-staged ref="staged" :data="staged"></gitamic-staged>

            <div class="flex my-3">
                <h2>Unstaged</h2>
            </div>
            <gitamic-unstaged ref="unstaged" :data="unstaged"></gitamic-unstaged>

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
                unstaged: [],
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
            async getStatus() {
                let response = await this.$axios.get(cp_url(`gitamic/api/status`))

                this.loaded = true;
                this.unstaged = response.data.unstaged;
                this.staged = response.data.staged;
                this.meta = response.data.meta;
            },
        }
    }
</script>
