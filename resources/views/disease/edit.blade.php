@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('disease.navbar')
        <div class="col-md-8 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="btn btn-link" href="{{ config('medicine.base_url') }}/diseaseDetail/{{ $disease['md_id'] }}" role="button">
                        {{ $disease['disease_name'] }}
                    </a>
                </div>

                <div class="panel-body">
                    <form class=" js-slidetitlebanners" id="postStatus">
                        <input name="md_id" type="hidden" class="form-control" id="diseaseId" value="{{ $disease['md_id'] }}" />
                        <div class="form-group">
                            <label for="disease_name" class="col-sm-2 control-label">病症:</label>
                            <div class="col-sm-10">
                                <input name="disease_name" type="text" class="form-control" id="diseaseName" autocomplete="off" value="{{ $disease['disease_name'] }}" placeholder="病症">
                            </div>
                        </div>
                        <div class="ml5 mt10 form-group">
                            <label for="disease_desc" class="col-sm-2 control-label">病症描述:</label>
                            <div class="col-sm-10">
                                <input name="disease_desc" type="text" class="form-control" id="diseaseDesc" autocomplete="off" value="{{ $disease['disease_desc'] }}" placeholder="病症描述">
                            </div>
                        </div>
                        <div class="form-group">
                            @if (isset($disease['alias']))
                                <input name='type' type="hidden" class="form-control" value="alias" />
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>别名</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disease['alias'] as $key => $post)
                                            <tr class="js-slide_one_block">
                                                <th scope="row">
                                                    <input name='diseases[{{ $key }}][mda_id]' type="hidden" class="form-control" value="{{ $post['mda_id'] }}" />
                                                </th>
                                                <td><input name='diseases[{{ $key }}][name]' class="" type="text" autocomplete="off" value="{{ $post['disease_alias'] }}" /></td>
                                                <td><button class="btn btn-danger js-del_slide" onclick="">删除</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @elseif (isset($disease['syndromes']))
                                <input name='type' type="hidden" class="form-control" value="syndrome" />
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>证候名称</th>
                                            <th>证候描述</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disease['syndromes'] as $key => $post)
                                            <tr class="js-slide_one_block">
                                                <th scope="row">
                                                    <input name='diseases[{{ $key }}][mts_id]' type="hidden" class="form-control" value="{{ $post['mts_id'] }}" />
                                                </th>
                                                <td>
                                                    <input name='diseases[{{ $key }}][name]' class="" type="text" autocomplete="off" value="{{ $post['syndrome_name'] }}" />
                                                </td>
                                                <td>
                                                    <input name='diseases[{{ $key }}][desc]' class="" type="text" autocomplete="off" value="{{ $post['syndrome_desc'] }}" />
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger js-del_slide" onclick="">删除</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <div class="form-group">
                            <input class="btn btn-success js-add_slide" type="button" value="增加" />
                            <input class="btn btn-primary" type="submit" value="保存" />
                        </div>
                    </form>
                    <script type="text/javascript" src="//dn-staticfile.qbox.me/jquery-serialize-object/2.0.0/jquery.serialize-object.compiled.js"></script>
                    <script>
                        $(function() {
                            bindslides();
                        });

                        function bindslides(){
                            //全局的增加和删除的绑定
                            $('.js-add_slide').on('click', function(e){
                                e.preventDefault();
                                var block = $(this).parents('form').find('.js-slide_one_block');
                                var tpl_dom = block.first().clone(true);
                                //数据置空
                                tpl_dom.find('input[type=text]').val('');
                                //更新index相关
                                tpl_dom.find('input[type=text]').each(function(){
                                    $(this).attr('name',$(this).attr('name').replace('[0]','['+block.length+']'));
                                });
                                tpl_dom.insertAfter(block.last());
                            });
                            $('.js-del_slide').on('click',function(e){
                                e.preventDefault();

                                if ($(this).parents('form').find('.js-slide_one_block').length > 1){
                                    $(this).parent().parent().remove();
                                }else{
                                    alert("至少要有一条数据!");
                                }
                            });
                            $('.js-slidetitlebanners').submit(function(e){
                                e.preventDefault();

                                var data =$('form#postStatus').serializeObject();
                                doSaveData(data);
                            });
                        }
                        //统一的向后台提交的处理
                        function doSaveData(data){
                            if (data.type == 'syndrome') {
                                $.post("{{ config('medicine.base_url') }}/doeditDiseaseSyndromes", {'_token':'{{csrf_token()}}', 'data':data}, function(res){
                                    res = $.parseJSON(res);
                                    if (res == '0') {
                                        alert('提交出错，请重新编辑');
                                    }else {
                                        setTimeout(self.location=document.referrer,'800');
                                    }
                                });
                            } else {
                                $.post("{{ config('medicine.base_url') }}/doeditDiseaseAlias", {'_token':'{{csrf_token()}}', 'data':data}, function(res){
                                    // res = $.parseJSON(res);
                                    if (res == '0') {
                                        alert('提交出错，请重新编辑');
                                    }else {
                                        setTimeout(self.location=document.referrer,'800');
                                    }
                                });
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
