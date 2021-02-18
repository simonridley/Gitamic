<template>
    <div>
        <confirmation-modal
            v-if="confirming"
            title="Commit staged changes"
            buttonText="Commit"
            @confirm="doCommit"
            @cancel="cancelCommit"
        >
            <p>Are you sure you want to commit these changes?</p>
            <ul class="m-2 list-inside">
                <li v-for="file in staged" class="list-disc">{{ file.relative_path }}</li>
            </ul>

            <label for="commit_message">Enter a commit message</label>
            <textarea v-model="commit_message" class="w-full border rounded font-mono p-2 h-48" id="commit_message"></textarea>
        </confirmation-modal>

        <div class="flex mb-3">
            <h1 class="flex-1">{{ __('Gitamic') }}</h1>
            <button class="btn" @click.prevent="getStatus">{{ __('Refresh') }}</button>
            <button
                v-if="hasStagedChanges"
                class="ml-2 btn-primary flex items-center"
                @click="confirmCommit">
                <span>{{ __('Commit') }}</span>
            </button>

            <button
                v-if="! isUpToDate"
                class="ml-2 btn-primary flex items-center"
                @click="push">
                <span>{{ __('Push') }}</span>
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
                confirming: false,
                unstaged: [],
                staged: [],
                meta: {},
                commit_message: '',
                up_to_date: true,
            }
        },

        computed: {
            hasStagedChanges() {
                return this.staged.length > 0;
            },

            isUpToDate() {
                return this.up_to_date;
            }
        },

        watch: {
        },

        created() {
            this.rows = this.getStatus();

            this.$events.$on('composer-finished', this.getStatus);
        },

        methods: {
            async getStatus() {
                let response = await this.$axios.get(cp_url(`gitamic/api/status`));

                this.loaded = true;
                this.unstaged = response.data.unstaged;
                this.staged = response.data.staged;
                this.meta = response.data.meta;
                this.up_to_date = response.data.up_to_date;
            },

            confirmCommit() {
                this.confirming = true;
            },

            cancelCommit() {
                this.confirming = false;
                this.commit_message = '';
            },

            async doCommit() {
                let response = await this.$axios.post(cp_url(`gitamic/api/commit`), {
                    commit_message: this.commit_message
                });
                await this.getStatus();
                this.confirming = false;
                this.commit_message = '';
            },

            async push() {
                await this.$axios.post(cp_url(`gitamic/api/push`));

                await this.getStatus();
            },
        }
    }
</script>
