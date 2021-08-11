<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Workflow </h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner" v-if="data">
                <div class="card-title-group align-start gx-3 mb-3">
                    <div class="card-title">
                        <h6 class="title">{{ edit ? 'Editing' : 'Viewing' }} #{{ $route.params.id }}</h6>
                    </div>
                    <div class="card-tools">
                        <nuxt-link to="/workflow" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                    </div>
                </div>
                <form @submit.prevent="update" class="gy-3">
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Workflow Name</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.workflow_name" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0" for="keyword">Document Type</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" v-model="data.document_type" :disabled="!edit">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label mb-0" for="keyword">Document State</label>

                        <div v-if="edit">
                        <button type="button" class="btn btn-primary" @click="openModal">Add Data</button>
                        <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchtransition="fetchtransition" @fetchdocstate="fetchdocstate" :processingId="processingId" :editState="editState" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed" :editAllowEdit="editAllowEdit" :editDocstatus="editDocstatus"/>
                        </div>

                        <table class="table table-tranx mt-4 is-compact">
                            <thead>
                                <tr class="tb-tnx-head">
                                    <th>
                                        <span class="d-md-inline-block d-none">No</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">State</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">Docstatus</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">Allow Edit</span>
                                    </th>
                                    <th v-if="edit">
                                        <span class="d-md-inline-block d-none">Actions</span>
                                    </th>
                                    <th>
                                        <span>&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tb-tnx-item" v-for="(docstate, index) in docstate" :key="index">
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ index + 1 }}</span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">
                                            <strong class="text-primary">{{ docstate.state }}</strong>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ docstate.docstatus }}</span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ docstate.allow_edit }}</span>
                                    </td>
                                    <td v-if="edit">
                                        <button type="button" class="btn btn-outline-primary btn-sm" @click="openEditDocstateModal(docstate.id, docstate.state, docstate.docstatus, docstate.allow_edit)">Edit</button>
                                        <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchtransition="fetchtransition" @fetchdocstate="fetchdocstate" :processingId="processingId" :editState="editState" :editDocstatus="editDocstatus" :editAllowEdit="editAllowEdit" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed"/>
                                        <button type="button" class="btn btn-outline-danger btn-sm" @click="deleteDocstate(docstate.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <label class="form-label mb-0" for="keyword">Workflow Transition</label>

                        <div v-if="edit">
                        <button type="button" class="btn btn-primary" @click="openTransitionModal">Add Data</button>
                        <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchtransition="fetchtransition" @fetchdocstate="fetchdocstate" :processingId="processingId" :editState="editState" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed" :editAllowEdit="editAllowEdit" :editDocstatus="editDocstatus"/>
                        </div>

                        <table class="table table-tranx mt-4 is-compact">
                            <thead>
                                <tr class="tb-tnx-head">
                                    <th>
                                        <span class="d-md-inline-block d-none">No</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">State</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">Action</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">Next State</span>
                                    </th>
                                    <th>
                                        <span class="d-md-inline-block d-none">Condition</span>
                                    </th>
                                    <th v-if="edit">
                                        <span class="d-md-inline-block d-none">Actions</span>
                                    </th>
                                    <th>
                                        <span>&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tb-tnx-item" v-for="(transition, index) in transitions" :key="index">
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ index + 1 }}</span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">
                                            <strong class="text-primary">{{ transition.state }}</strong>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ transition.action }}</span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ transition.next_state }}</span>
                                    </td>
                                    <td>
                                        <span class="d-md-inline-block d-none">{{ transition.condition }}</span>
                                    </td>
                                    <td v-if="edit">
                                        <button type="button" class="btn btn-outline-primary btn-sm" @click="openEditTransitionModal(transition.id, transition.state, transition.action, transition.next_state, transition.condition, transition.allow_self_approval, transition.allowed)">Edit</button>
                                        <modal v-show="isModalVisible" @close-modal="closeModal" :parentNames="parentNames" :saveType="saveType" :inputType="inputType" @fetchtransition="fetchtransition" @fetchdocstate="fetchdocstate" :processingId="processingId" :editState="editState" :editAction="editAction" :editNextState="editNextState" :editCondition="editCondition" :editAllowSelfApproval="editAllowSelfApproval" :editAllowed="editAllowed"
                                        :editAllowEdit="editAllowEdit" :editDocstatus="editDocstatus"/>
                                        <button type="button" class="btn btn-outline-danger btn-sm" @click="deleteTransition(transition.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row g-3 align-center">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label mb-0">Status</label>
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="form-group">
                                <Label :active="data.is_active"/>
                            </div>
                        </div>
                    </div>
                    <div class="justify-content-end d-flex" v-if="edit">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
                <div v-if="!edit" class="mt-5">
                    <strong>
                        Metadata Information
                    </strong>

                    <hr>
                    <Metadata :data="data"/>

                    <div class="g-3 d-flex justify-content-between">
                        <div>
                            <change-active
                                :active="data.is_active"
                                :callback="changeActive"
                            />
                        </div>
                        <div>
                            <button type="button" class="btn btn-light" @click="edit = true" >Edit</button>
                            <nuxt-link to="/workflow/create" class="btn btn-primary">Add New Data</nuxt-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@/components/Editor';
import Label from '@/components/Label';
import ChangeActive from "@/components/ChangeActive";
import modal from "@/components/Modal";

export default {
    components: {
        Editor,
        Label,
        ChangeActive,
        modal,
    },
    data () {
        return {
            filter: {
                keyword: '',
                is_active: ''
            },
            data: null,
            edit: false,
            workflows: [],
            docstate: {},
            transitions: {},
            isModalVisible: false,
            parentNames: null,
            saveType: 'perm',
            inputType: '',
            processingId: '',
            editState: '',
            editAction: '',
            editNextState: '',
            editCondition: '',
            editAllowSelfApproval: '',
            editAllowed: '',
            editDocstatus: '',
            editAllowEdit: '',
        };
    },
    
    methods: {
        openModal() { 
            this.parentNames = this.$route.params.id;
            this.inputType = 'docstate';
            this.isModalVisible = true;
        },
        openTransitionModal() {
            this.parentNames = this.$route.params.id;
            this.inputType = 'transition';
            this.isModalVisible = true;
        },
        openEditTransitionModal(id, state, action, nextstate, condition, selfapproval, allowed) {
            this.processingId = id;
            this.editState = state;
            this.editAction = action;
            this.editNextState = nextstate;
            this.editCondition = condition;
            this.editAllowSelfApproval = selfapproval;
            this.editAllowed = allowed;
            this.parentNames = this.$route.params.id;
            this.inputType = 'edittransition';
            this.isModalVisible = true;
        },
        openEditDocstateModal(id, state, docstatus, allowedit) {
            // console.log(id);
            this.processingId = id;
            this.editState = state;
            this.editDocstatus = docstatus;
            this.editAllowEdit = allowedit;
            this.parentNames = this.$route.params.id;
            this.inputType = 'editdocstate';
            this.isModalVisible = true;
        },
        deleteDocstate(id) {
            this.processingId = id;

            this.$axios.$delete(`/api/v1/workflow/deletedocstate/${this.processingId}`)
                .then((response) => {
                    this.fetchdocstate();
                });
        },
        deleteTransition(id) {
            this.processingId = id;

            this.$axios.$delete(`/api/v1/workflow/deletetransition/${this.processingId}`)
                .then((response) => {
                    this.fetchtransition();
                });
        }, 
        closeModal() {
            this.inputType = '';
            this.processingId = '';
            this.editState = '';
            this.editAction = '';
            this.editNextState = '';
            this.editCondition = '';
            this.editAllowSelfApproval = '';
            this.editAllowed = '';
            this.editDocstatus = '';
            this.editAllowEdit = '';
            this.isModalVisible = false;
        },

        parse (data) {
            this.data = data.data;
            if (this.data.workflow) {
                this.workflows.push(this.data.workflow);
            }
        },
        fetch () {
            this.$axios.$get(`/api/v1/workflow/${this.$route.params.id}`, { params: { d: new Date().getTime() } })
                .then((data) => {
                    this.parse(data);
                });
        },

        fetchdocstate () {
            const filter = { ...this.filter }

            if (filter.is_active === '') {
                delete filter.is_active
            }

            if (filter.keyword === '') {
                filter.keyword = this.$route.params.id
            }

            this.$axios.$post(`/api/v1/workflow/docstate`, filter)
                .then((response) => {
                    this.docstate = response.data.data
                })
        },

        fetchtransition () {
            const filter = { ...this.filter }

            if (filter.is_active === '') {
                delete filter.is_active
            }

            if (filter.keyword === '') {
                filter.keyword = this.$route.params.id
            }

            this.$axios.$post(`/api/v1/workflow/transition`, filter)
                .then((response) => {
                    this.transitions = response.data.data
                })
        },

        update () {
            const data = { ...this.data, d: new Date().getTime() }

            this.$axios.$patch(`/api/v1/workflow/${this.data.id}`, data)
                .then((data) => {
                    this.parse(data);
                    this.edit = false;
                });
        },
        changeActive() {
            this.$axios
                .$post(
                `/api/v1/workflow/${this.data.id}/change_active`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
            this.$axios
                .$post(
                `/api/v1/workflow/${this.data.id}/change_delete`,
                {
                    d: new Date().getTime(),
                }
                )
                .then((data) => {
                this.parse(data);
                });
        }
    },
    created () {
        this.fetch();
        this.fetchdocstate();
        this.fetchtransition();
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
