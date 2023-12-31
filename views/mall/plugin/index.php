<?php
/**
 * author: opmall
 * Created by IntelliJ IDEA
 * Date Time: 2020/05/26 17:24
 */
?>
<style>
.cat-group {
    margin-bottom: 20px;
}

.cat-name {
    font-size: 18px;
    padding: 12px 0;
}

.plugin-list {
    flex-wrap: wrap;
    margin-left: -20px;
}

.plugin-item {
    background: #fff;
    border: 1px solid #ebebeb;
    width: 306px;
    height: 112px;
    overflow: hidden;
    margin: 0 0 16px 16px;
    padding: 16px;
    cursor: pointer;
    position: relative;
}

.plugin-icon-bg {
    border-radius: 10px;
    font-size: 0;
    display: inline-block;
    margin-right: 16px;
}

.plugin-icon {
    width: 80px;
    height: 80px;

}

.plugin-name,
.plugin-desc {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.plugin-name {
    font-size: 14px;
    margin-bottom: 4px;
}

.plugin-desc {
    color: #999999;
    font-size: 12px;
}

.plugin-btn {
    color: #545454;
    background: #f2f6fc;
    border: none;
}

.img-gray {
    -webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    -o-filter: grayscale(100%);
    filter: grayscale(100%);
    filter: gray;
    opacity: .5;
}

.search-input .el-input__inner {
    border-color: #fff;
    border-radius: 4px 0 0 4px;
}

.search-btn {
    border-radius: 0 4px 4px 0;
}

.lock-block {
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.35);
    z-index: 10;
    width: 100%;
    height: 100%;
}

.lock-block .iconfont {
    position: absolute;
    bottom: 16px;
    right: 16px;
    color: #fff;
    font-size: 35px;
    line-height: 1;
}
.el-button+.el-button {
    margin-left: 20px;
}
</style>
<div id="app" v-cloak>
    <div flex="box:last">
        <div>
            <el-button v-if="pluginHasUpdateCount>0" @click="updateAll" type="warning"><?= \Yii::t('mall/plugin', '更新全部');?>
            </el-button>
        </div>
        <div flex class="search-group">
            <el-input style="width: 250px"
                      class="search-input"
                      placeholder="<?= \Yii::t('mall/plugin', '请输入想查找的插件名称');?>"
                      v-model="searchKeyword"
                      clearable
                      @clear="searchSubmit"
                      @keyup.enter.native="searchSubmit"></el-input>
            <el-button class="search-btn" type="primary" @click="searchSubmit"><?= \Yii::t('mall/plugin', '搜索');?></el-button>
            <template v-if="isSuperAdmin">
                <el-button type="primary" @click="$navigate({r: 'mall/plugin/cat-manager'})"><?= \Yii::t('mall/plugin', '编辑应用');?></el-button>
            </template>
        </div>
    </div>
    <div v-if="isSearching && inSearchCount==0"
         style="min-height: calc(100vh - 140px); color: #999;"
         flex="cross:center main:center">
        <div style="text-align: center;">
            <img src="<?= \Yii::$app->request->baseUrl?>/statics/img/mall/search-empty.png" style="width: 150px;height: 150px;">
            <div><?= \Yii::t('mall/plugin', '暂无搜索结果');?></div>
        </div>
    </div>
    <template v-for="(cat, catIndex) in cats">
        <template v-if="!isSearching || cat.inSearch">
            <div :key="catIndex" class="cat-group" :id="cat.name">
                <div class="cat-name">{{cat.display_name}}</div>
                <div class="plugin-list" flex="dir:left">
                    <template v-for="(plugin, pluginIndex) in cat.plugins">
                        <template v-if="!isSearching || plugin.inSearch">
                            <div :key="pluginIndex" @click="entryPlugin(plugin)" :class="[{'lock' : plugin.is_delete==1}, 'plugin-item']" flex="dir:left box:first">
                                <div style="z-index: 1">
                                    <div class="plugin-icon-bg" :style="{background: cat.color?cat.color:'#409EFF',}">
                                        <img class="plugin-icon" :src="plugin.pic_url">
                                    </div>
                                </div>
                                <div flex="dir:top box:last" style="z-index: 1">
                                    <div>
                                        <div class="plugin-name">{{plugin.display_name}}</div>
                                        <div class="plugin-desc">{{plugin.desc}}</div>
                                    </div>
                                    <div style="text-align: right; display: flex; align-items: center;justify-content: space-between; margin-top: 36px;">
                                        <el-button @click.stop="entryDetail(plugin, cat, 1)" class="plugin-btn" size="mini" type="info" round>
                                            <?= \Yii::t('mall/plugin', '详情');?>
                                        </el-button>

                                        <el-button circle size="mini" type="text" @click.stop="topNav(plugin, 2)" round v-if="plugin.is_nav == 1">
                                            <el-tooltip class="item"  effect="dark" content="<?= \Yii::t('mall/mall_member', '移除顶部导航');?>" placement="top">
                                                <img src="statics/img/mall/collect.png" alt="" width="20">
                                            </el-tooltip>
                                        </el-button>
                                        <el-button circle size="mini" type="text" @click.stop="topNav(plugin, 1)" round v-if="plugin.is_nav == 0">
                                            <el-tooltip class="item"  effect="dark" content="<?= \Yii::t('mall/mall_member', '加入顶部导航');?>" placement="top">
                                                <img src="statics/img/mall/uncollect.png" alt="" width="20">
                                            </el-tooltip>
                                        </el-button>
                                        <template v-if="plugin.show_detail && plugin.is_delete!=1">
                                            <template v-if="plugin.is_delete == 1">
                                                <el-button @click.stop="entryDetail(plugin, cat)" class="plugin-btn" size="mini" round>
                                                    <?= \Yii::t('mall/plugin', '安装');?>
                                                </el-button>
                                            </template>
                                            <template v-if="plugin.new_version">
                                                <el-button @click.stop="updateItem(catIndex, pluginIndex)"
                                                           style="border: none"
                                                           size="mini"
                                                           type="warning"
                                                           round><?= \Yii::t('mall/plugin', '更新');?>
                                                </el-button>
                                            </template>
                                        </template>
                                    </div>
                                </div>
                                <div @click.stop="entryDetail(plugin, cat)" class="lock-block" v-if="plugin.is_delete==1">
                                    <i class="iconfont icon-lock"></i>
                                </div>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </template>
    </template>
</div>
<script>
new Vue({
    el: '#app',
    data() {
        return {
            cats: [],
            plugins: null,
            loading: false,
            searchKeyword: '',
            isSearching: false,
            pluginHasUpdateCount: 0,
            isSuperAdmin: _isSuperAdmin,
            inSearchCount: 0,
        };
    },
    created() {
        this.loadData();
    },
    methods: {
        loadData() {
            this.loading = true;
            this.$request({
                params: {
                    r: 'mall/plugin/index',
                    cat_name: getQuery('cat_name'),
                },
            }).then(e => {
                this.loading = false;
                if (e.data.code === 0) {
                    let cats = e.data.data.cats;
                    for (let i = 0; i < cats.length; i++) {
                        cats[i].inSearch = true;
                        for (let j = 0; j < cats[i].plugins.length; j++) {
                            cats[i].plugins[j].inSearch = true;
                            cats[i].plugins[j].new_version = false;
                        }
                    }
                    this.cats = cats;
                    // this.checkUpdateList();
                }
            }).catch(() => {
                this.loading = false;
            });
        },
        searchSubmit() {
            if (this.searchKeyword === null || this.searchKeyword === '' || !this.searchKeyword.length) {
                this.isSearching = false;
                return;
            }
            this.isSearching = true;
            let inSearchCount = 0;
            for (let i in this.cats) {
                this.cats[i].inSearch = false;
                for (let j in this.cats[i].plugins) {
                    this.cats[i].plugins[j].inSearch = this.cats[i].plugins[j].display_name.indexOf(this.searchKeyword) >= 0;
                    if (this.cats[i].plugins[j].inSearch) {
                        this.cats[i].inSearch = true;
                        inSearchCount++;
                    }
                }
            }
            this.inSearchCount = inSearchCount;
        },
        entryPlugin(plugin) {
            if (!plugin.route) {
                if(plugin.route === ''){
                    return;
                }
                this.$message.warning('<?= \Yii::t('admin/app_manage', '请联系客服或者销售咨询');?>');
                return;
            }
            navigateTo({r: plugin.route});
        },
        entryDetail(plugin, cat, type = 0) {
            let catName = getQuery('cat_name');
            let catDisplayName = cat.display_name;
            if (!catName) {
                catDisplayName = '<?= \Yii::t('mall/plugin', '全部应用');?>';
            }
            if(!plugin.show_detail && (!plugin.is_buy || plugin.is_delete === 1)){
                this.$message.warning('<?= \Yii::t('admin/app_manage', '请联系客服或者销售咨询');?>');
                return;
            }
            if(type === 1 && plugin.external_link){
                window.open(plugin.external_link);
            }else {
                navigateTo({
                    r: 'mall/plugin/detail',
                    name: plugin.name,
                    cat_color: cat.color,
                    cat_name: catName ? catName : '',
                    cat_display_name: catDisplayName,
                });
            }
        },
        cPluginUpdateTip(item) {
            return '<?= \Yii::t('mall/plugin', '有更新，最新版本');?>v' + item.new_version.version + '<?= \Yii::t('mall/plugin', '当前版本');?>v' + item.version;
        },
        // checkUpdateList() {
        //     if (!_isSuperAdmin) {
        //         return;
        //     }
        //     this.$request({
        //         params: {
        //             r: 'mall/plugin/check-update-list',
        //         },
        //     }).then(e => {
        //         if (e.data.code === 0) {
        //             const list = e.data.data.list;
        //             for (let i in list) {
        //                 for (let j in this.cats) {
        //                     for (let k in this.cats[j].plugins) {
        //                         if (this.cats[j].plugins[k].name === list[i].name && list[i].new_version) {
        //                             this.pluginHasUpdateCount++;
        //                             this.cats[j].plugins[k].new_version = list[i].new_version;
        //                             this.cats[j].plugins[k].remote_id = list[i].id;
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }).catch(() => {
        //     });
        // },
        updateAll() {
            const count = this.pluginHasUpdateCount;
            this.$confirm(`<?= \Yii::t('mall/plugin', '确认更新');?>${count}<?= \Yii::t('mall/plugin', '个插件');?>`).then(() => {
                const updateList = [];
                for (let catIndex in this.cats) {
                    for (let pluginIndex in this.cats[catIndex].plugins) {
                        const plugin = this.cats[catIndex].plugins[pluginIndex];
                        if (plugin.new_version) {
                            updateList.push(plugin);
                        }
                    }
                }
                const syncUpdate = (i) => {
                    if (!updateList[i]) {
                        this.$alert('<?= \Yii::t('mall/plugin', '更新完成');?>', '<?= \Yii::t('mall/plugin', '提示');?>');
                        return;
                    }
                    this.updatePlugin(updateList[i].remote_id, updateList[i].name).then(e => {
                        console.log('update success: ', e);
                        syncUpdate(i + 1);
                    }).catch(e => {
                        console.log('update fail: ', e);
                        if (e) {
                            this.$alert(e, '<?= \Yii::t('mall/plugin', '提示');?>');
                        }
                    });
                };
                syncUpdate(0);
            }).catch(() => {
            });
        },
        topNav(plugin, type){
            request({
                params: {
                    r: 'mall/plugin/top-nav',
                },
                method: 'post',
                data: {
                    name:  plugin.name,
                    type: type,
                }
            }).then(res => {
                if (res.data.code === 0) {
                    this.$message.success(res.data.msg);
                    setTimeout(function(){
                        location.reload();
                    },300);
                } else {
                    this.$message.error(res.data.msg);
                }
            });
        },
        updateItem(catIndex, pluginIndex) {
            const item = this.cats[catIndex].plugins[pluginIndex];
            this.$confirm(`<?= \Yii::t('mall/plugin', '确认更新');?>${item.display_name}？`).then(() => {
                this.allPluginUpdating = true;
                item.updating = true;
                this.updatePlugin(item.remote_id, item.name).then(() => {
                    this.$alert('<?= \Yii::t('mall/plugin', '插件更新完成');?>').then(() => {
                        location.reload();
                    });
                }).catch(msg => {
                    if (!msg) {
                        msg = '<?= \Yii::t('mall/plugin', '安装未完成');?>';
                    }
                    this.$alert(msg).then(() => {
                        location.reload();
                    });
                });
            }).catch(() => {
            });
        },
        updatePlugin(id, name) {
            const download = (id) => {
                return new Promise((resolve, reject) => {
                    this.$request({
                        params: {
                            r: 'mall/plugin/download',
                            id: id,
                        },
                    }).then(e => {
                        if (e.data.code === 0) {
                            resolve();
                        } else {
                            reject(e.data.msg);
                        }
                    }).catch(e => {
                        reject();
                    });
                });
            };
            const install = (name) => {
                return new Promise((resolve, reject) => {
                    this.$request({
                        params: {
                            r: 'mall/plugin/install',
                            name: name,
                        },
                    }).then(e => {
                        if (e.data.code === 0) {
                            resolve();
                        } else {
                            reject(e.data.msg);
                        }
                    }).catch(e => {
                    });
                });
            };
            return new Promise((resolve, reject) => {
                download(id).then(() => {
                    install(name).then(() => {
                        resolve();
                    }).catch(msg => {
                        reject(msg);
                    });
                }).catch(msg => {
                    reject(msg);
                });
            });
        },
    }
});
</script>
