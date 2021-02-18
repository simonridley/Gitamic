<template>
    <div>
        <data-list ref="list" :visibleColumns="columns" :columns="columns" :rows="rows" sortColumn="path" sortDirection="asc">
            <div class="card p-0 relative" slot-scope="{ filteredRows: rows }">
                <data-list-bulk-actions url="api/actions/unstaged" @completed="refresh" />

                <data-list-table :rows="rows" allow-bulk-actions="true">
                    <template slot="cell-relative_path" slot-scope="{ row: file }">
                        <a :href="file.edit_url" v-if="file.is_content">{{ file.relative_path }}</a>
                    </template>
                    <template slot="actions" slot-scope="{ row: file, index }">
                        <dropdown-list>
                            <dropdown-item :text="__('Stage')"
                                           key="git.stage"
                                           @click="stage(file)" />
                            <div class="divider"></div>
<!--                            <dropdown-item :text="__('Delete')" class="warning" />-->
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
            'data',
        ],

        data() {
            return {
                rows: this.data,
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
            }
        },

        computed: {
        },

        watch: {
            data(newValue, oldValue) {
                this.rows = newValue;
            }
        },

        created() {
        },

        methods: {
            refresh() {
                this.$refs.list.clearSelections();
                this.$root.$refs.status.getStatus();
            },
            stage(file) {
                let payload = {
                    selections: [file.id],
                    action: 'stage',
                };
                this.$axios.post('api/actions/unstaged', payload, { responseType: 'blob' }).then(response => {
                    this.refresh();
                });
            },
        }
    }
</script>
