<template>
    <div>
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Workflow State</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-inner">
                <div>
                    <div class="card-title-group align-start gx-3 mb-3">
                        <div class="card-title">
                            <h6 class="title">Add New Workflow State</h6>
                        </div>
                        <div class="card-tools">
                            <nuxt-link to="/workflowstate" class="btn btn-primary btn-dim"><em class="icon ni ni-back-ios"></em><span>Back to List</span></nuxt-link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="gy-3">
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Workflow State Name</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.workflow_state_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label class="form-label mb-0" for="keyword">Style</label>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" v-model="data.style">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 align-center">
                            <div class="col-lg-2">
                            </div>
                            <div class="col-lg-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@/components/Editor'

export default {
    components: {
        Editor
    },
    data () {
        return {
            data: {
                workflow_state_name: '',
                style: ''
            }
        }
    },
    methods: {
        submit () {
            const data = { ...this.data, d: new Date().getTime() }
            // console.log(data)
            this.$axios.$put('/api/v1/workflowstate', data)
                .then((data) => {
                    this.$router.push({ path: `/workflowstate/detail/${data.data.id}` })
                })
        },
    }
}
</script>

<style scoped>
@import "@/static/flag/css/flag-icon.min.css";
</style>
