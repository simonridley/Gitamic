<template>
    <div>
        <div v-if="! loaded" class="card p-3 text-center">
            <loading-graphic  />
        </div>

        <data-list :visibleColumns="columns" :columns="columns" :rows="untracked" v-if="loaded" sortColumn="path" sortDirection="asc">
            <div class="card p-0 relative" slot-scope="{ filteredRows: rows }">
                <data-list-bulk-actions url="api/actions/untracked" />

                <data-list-table :rows="rows" allow-bulk-actions="true">
                    <template slot="cell-relative_path" slot-scope="{ row: file }">
                        <a :href="file.edit_url" v-if="file.is_content">{{ file.relative_path }}</a>
                    </template>
                    <template slot="actions" slot-scope="{ row: file, index }">
                        <dropdown-list>
                            <dropdown-item :text="__('Add')" />
                            <dropdown-item :text="__('Stash')" />
                            <dropdown-item :text="__('Ignore')" />
                            <div class="divider"></div>
                            <dropdown-item :text="__('Delete')" class="warning" />
<!--                            /**@click="destroy(container, index)"**/-->
                        </dropdown-list>
                    </template>
                </data-list-table>
            </div>
        </data-list>
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
                columns: [
                    {
                        'field': 'relative_path',
                        'label': 'Path',
                    },
                    {
                        'field': 'last_modified',
                        'label': 'Last modified',
                        'fieldtype': 'date',
                    }
                ],
                actions: [
                    {
                        'title': 'Add to Git',
                        'handle': 'add',
                    }
                ],
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
//                this.$axios.get(window.Statamic.$config.get('cpRoot')+'/gitamic/api/status').then(response => {
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
