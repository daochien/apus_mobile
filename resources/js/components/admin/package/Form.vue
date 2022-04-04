<template>
    <div class="row justify-content-center">
        <div class="col-md-12 text-right" style="margin-bottom: 10px;">
            <a href="/admin/packages" class="btn btn-responsive btn-warning">Hủy bỏ</a>
            <button @click.prevent="edit(form.id)" v-if="editModel" type="submit" class="btn btn-responsive btn-info">Cập nhật</button>
            <button @click.prevent="saveForm()" v-else class="btn btn-responsive btn-primary">Tạo mới</button>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="input">Tên gói(*)</label>
                        <input v-model="form.name" :class="['form-control', { 'is-invalid': form.errors.has('name') }]">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label for="input">Giá bán(*)</label>
                        <input type="number" v-model="form.price" :class="['form-control', { 'is-invalid': form.errors.has('price') }]">
                        <has-error :form="form" field="price"></has-error>
                    </div>

                    <div class="form-group">
                        <label for="input">Ảnh đại diện</label>
                        <div data-v-65473a7c="" class="d-flex justify-content-start">

                            <img
                                :src="avatarPreview"
                                style="border: 1px solid"
                                width="100"
                                height="100"
                            />
                            <div data-v-65473a7c="" class="d-flex align-items-center ml-3">
                                <input
                                    type="file"
                                    @change="onSelectImageAvatar($event)"
                                    :class="['form-control', {'is-invalid': form.errors.has('avatar') }]"
                                    id="icon"
                                    placeholder="Icon"
                                    accept="image/*"
                                    ref="fileupload"
                                />
                                <has-error :form="form" field="avatar"></has-error>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="input">Trạng thái</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.status" id="status1" value="1">
                                <label class="form-check-label" for="status1">Activce</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" v-model="form.status" id="status2" value="0">
                                <label class="form-check-label" for="status2">InActive</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="input">Mô tả</label>
                        <textarea rows="5" v-model="form.desc" :class="['form-control', { 'is-invalid': form.errors.has('desc') }]"></textarea>
                        <has-error :form="form" field="desc"></has-error>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="input">Chọn source mẫu(*)</label>
                            <select @change="loadConfigs()" v-model="form.source_id" :class="['form-control', { 'is-invalid': form.errors.has('source_id') }]">
                                <option value="">--Chọn--</option>
                                <option v-for="(item, index) in sources" :value="item.id" :key="index">{{ item.name }}</option>
                            </select>
                            <has-error :form="form" field="source_id"></has-error>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="input">Chọn config</label>
                            <select v-model="itemConfigSelected" :class="['form-control']">
                                <option value="">--Chọn--</option>
                                <option v-for="(item, index) in sourceConfigs" :value="item.id" :key="index">{{ item.key }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="input">Action</label>
                            <div>
                                <button class="btn btn-sm btn-warning" @click="addItem()"> Chọn</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card-body" style="background: #fff;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="border-top-0" scope="col"></th>
                        <th class="border-top-0" scope="col">Key</th>
                        <th class="border-top-0" scope="col">Type</th>
                        <th class="border-top-0" scope="col">Can Edit</th>
                        <th class="border-top-0" scope="col">Old Value</th>
                        <th class="border-top-0" scope="col">New Value</th>
                        <th class="border-top-0" scope="col">Tác vụ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template  v-for="(config, index) in packageConfigs" >
                        <tr
                            style="font-weight:bold;"
                            class="clickable"
                            data-toggle="collapse"
                            :key="index"
                            :id="`row${index}`" :data-target="`.row${index}`">
                            <td>
                                <template v-if="config.is_group == 1">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </template>
                            </td>
                            <td>
                                {{ config.key }}
                            </td>
                            <td>{{ config.type ? config.type: '-' }}</td>
                            <td>
                                {{ config.is_edit ? 'Yes': 'No' }}
                            </td>
                            <td>
                                <template v-if="config.type === 'file'">
                                    <img v-if="!editModel" width="50" :src="config.image" alt="">
                                    <img v-else width="50" :src="config.value" alt="">
                                </template>
                                <template v-else>
                                    {{ config.value? config.value: '-' }}
                                </template>
                            </td>

                            <td>
                                <template v-if="config.type === 'checkbox' || config.type === 'radio'">
                                    <input-tag placeholder="Enter..." v-model="config.new_value" :limit="10"></input-tag>
                                </template>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm " @click="removeGiftItem(index)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <template v-if="config.items.length > 0 && config.is_group == 1" v-for="(child, i) in config.items" >
                            <tr :class="`collapse row${index}`" :key="`child_${index}_${i}`">
                                <td>
                                    -
                                </td>
                                <td>{{ child.key }}</td>
                                <td>{{ child.type }}</td>
                                <td>
                                    {{ child.is_edit ? 'Yes': 'No' }}
                                </td>
                                <td>
                                    <template v-if="child.type === 'file'">
                                        <img v-if="!editModel" width="50" :src="child.image" alt="">
                                        <img v-else width="50" :src="child.value" alt="">
                                    </template>
                                    <template v-else>
                                        {{ child.value? child.value: '-' }}
                                    </template>
                                </td>
                                <td>
                                    <template v-if="child.type === 'checkbox' || child.type === 'radio'">
                                        <input-tag placeholder="Enter..." v-model="child.new_value" :limit="10"></input-tag>
                                    </template>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm " @click="removeChildItem(index, i)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>
<script>

const img_link ="/images/no_image.jpg";

export default {
    props: ['editModel', 'sources', 'item'],
    data () {
        return {
            form: new Form({
                name: '',
                avatar: '',
                source_id: '',
                desc: '',
                status: 1,
                price: '',
                configs: []
            }),
            avatarPreview: img_link,
            sourceConfigs: [],
            itemConfigSelected: '',
            packageConfigs: []
        }
    },
    created () {
        if (this.editModel) {
            this.loadEdit(this.item);
        }
    },
    methods: {
        loadConfigs () {
            let that = this;
            this.sources.forEach ((item) => {
                if (item.id == that.form.source_id) {
                    that.sourceConfigs = item.configs;
                    if (!that.editModel) {
                        that.packageConfigs = [];
                        that.packageConfigs = JSON.parse(JSON.stringify(item.configs));
                    }
                }
            })
        },
        addItem () {
            let that = this;
            if (this.itemConfigSelected) {
                let canPush = true;
                this.packageConfigs.forEach((item) => {
                    if (item.id == that.itemConfigSelected) {
                        canPush = false;
                    }
                })
                if (canPush) {
                    this.sourceConfigs.forEach( (item) => {
                        if (item.id == that.itemConfigSelected) {
                            if (item.type === 'checkbox' || item.type === 'radio') {
                                item.new_value = JSON.parse(item.value);
                            } else {
                                item.new_value = item.value;
                            }

                            that.packageConfigs.push(JSON.parse(JSON.stringify(item)));
                        }
                    })
                } else {
                    alert('Config này đã được thêm vào danh sách')
                }
            }
        },
        beforeAddTag (tag) {
            return item;
        },
        removeGiftItem (index) {
            this.packageConfigs.splice(index, 1);
        },
        removeChildItem (index, i) {
            this.packageConfigs[index].items.splice(i, 1);
        },
        onSelectImageAvatar (e) {
            try {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length) return;
                if (/\.(jpe?g|png|gif)$/i.test(files[0].name)) {
                    this.form.avatar = files[0];
                    this.avatarPreview = URL.createObjectURL(files[0]);
                } else {
                    this.form.avatar = "";
                    if (!this.isEdit) {
                        this.form.avatarPreview = "";
                    }
                    Toast.fire({
                        icon: "error",
                        title: "Image not valid",
                    });
                }
            } catch (error) {}
        },

        saveForm () {
            this.form.configs = JSON.stringify(this.packageConfigs);
            this.form.submit('post', '/admin/packages', {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
                .then(({data}) => {
                    if (data.status) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Tạo mới source mẫu thành công'
                        });
                        window.location.href = '/admin/packages';
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                    }

                }).catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống'
                });
            })
        },
        loadEdit (item) {
            let that = this;
            this.form.id = item.id;
            this.form.name = item.name;
            this.form.desc = item.desc;
            this.form.status = item.status;
            this.form.price = item.price;
            this.form.source_id = item.source_id;
            this.avatarPreview = item.avatar ? item.avatar : img_link;
            this.loadConfigs();
            this.packageConfigs = JSON.parse(item.configs);
            //console.log(configs)
            // if (package.configs.length > 0) {
            //     this.configs = [];
            //     package.configs.forEach((item) => {
            //         let value = '';
            //         if (item.type === 'radio' || item.type === 'checkbox') {
            //             value = JSON.parse(item.value);
            //         } else if (item.type === 'file') {
            //             value = '';
            //         } else {
            //             value = item.value;
            //         }
            //         that.configs.push({
            //             'id': item.id,
            //             'key': item.key,
            //             'type': item.type,
            //             'value': value,
            //             'is_edit': item.is_edit == 1? true: false
            //         })
            //     })
            // }

        },
        edit (id) {
            this.form.configs = JSON.stringify(this.configs);
            this.form.submit('post', '/admin/packages/'+id, {
                transformRequest: [function (data, headers) {
                    return window.objectToFormData.serialize(data)
                }],
            })
                .then(({data}) => {
                    let status = data.status ? 'success': 'error';

                    Toast.fire({
                        icon: status,
                        title: data.message
                    });

                    if (data.status)
                        window.location.href = '/admin/packages';

                }).catch((error) => {
                Toast.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống'
                });
            })
        }
    }
}
</script>
