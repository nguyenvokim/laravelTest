<template>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    <span>Short link manager</span>
                    <button class="btn btn-primary pull-right" @click="addNewLink">
                        <i class="fa fa-fw fa-plus"></i> Add new
                    </button>
                    <modal v-model="openPopup" :size="'md'" title="Add/Edit link">
                        <div class="form">
                            <div class="form-group">
                                <label class="control-label">
                                    Redirect link
                                </label>
                                <div class="">
                                    <input type="text" class="form-control" v-model="editModel.link_redirect" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    Status
                                </label>
                                <div class="">
                                    <select class="form-control" v-model="editModel.status">
                                        <option v-bind:value="0">De-Active</option>
                                        <option v-bind:value="1">Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div slot="footer">
                            <button class="btn btn-default" @click="openPopup = false">
                                <i class="fa fa-fw fa-check"></i>
                                Cancel
                            </button>
                            <button v-common-button-loading="onLoading" class="btn btn-danger" @click="saveLink">
                                <i class="fa fa-fw fa-save"/>
                                Save
                            </button>
                        </div>
                    </modal>
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Link</th>
                        <th>Redirect link</th>
                        <th>View count</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="link in links">
                        <td>{{ link.id }}</td>
                        <td>
                            <a :href="'/link/' + link.link_code" target="_blank">{{ link.link_code }}</a>
                        </td>
                        <td>{{ link.link_redirect }}</td>
                        <td>{{ link.view_count }}</td>
                        <td>
                            <span class="label" :class="{'label-danger': link.status == 0, 'label-success': link.status == 1}"
                                  v-text="link.status == 1 ? 'Active' : 'De-Active'">

                            </span>
                        </td>
                        <td>
                            <div class="btn btn-group btn-group-xs">
                                <button class="btn btn-primary btn-xs" @click="editLink(link)">
                                    <i class="fa fa-fw fa-edit"></i> Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <pagination @change="loadData" align="center" v-model="page" :total-page="totalLink"/>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                links: [],
                openPopup: false,
                onLoading: false,
                editModel: {
                    id: 0,
                    link_redirect: '',
                    status: 1
                },
                totalLink: 0,
                page: 1,
            }
        },
        mounted() {
            this.loadData();
        },
        methods: {
            loadData: function () {
                axios.get(`/api/links?page=${this.page}`).then((res) => {
                    this.links = res.data.data;
                    this.totalLink = res.data.last_page;
                })
            },
            addNewLink: function () {
                this.editModel = {
                    id: 0,
                    link_redirect: '',
                    status: 1
                }
                this.openPopup = true;
            },
            editLink: function (link) {
                this.editModel = link;
                this.openPopup = true;
            },
            saveLink: function () {
                if (this.onLoading) {
                    return;
                };
                if (this.editModel.link_redirect.length < 3) {
                    this.$notify({
                        type: 'warning',
                        title: "Warning",
                        content: "Please enter redirect link"
                    });
                    return;
                }
                this.onLoading = true;
                axios.post('/api/links', this.editModel).then(() => {
                    this.onLoading = false;
                    this.openPopup = false;
                    this.loadData();
                })
            }
        }
    }
</script>
