<?php

namespace Dcat\Admin\XqwLog\Http\Controllers;

use Dcat\Admin\Grid;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\XqwLog\Models\XqwLog;
use Dcat\Admin\XqwLog\XqwLogServiceProvider;
use Dcat\Admin\Support\Helper;
use Illuminate\Support\Arr;

class XqwLogController
{
    public function index(Content $content)
    {
        return $content
            ->title(XqwLogServiceProvider::trans('log.title'))
            ->description(trans('admin.list'))
            ->body($this->grid());
    }

    protected function grid()
    {
        return new Grid(XqwLog::with('user'), function (Grid $grid) {
            $grid->column('id', 'ID')->sortable();
            $grid->column('user', trans('admin.user'))
                ->display(function ($user) {
                    if (! $user) {
                        return;
                    }

                    $user = Helper::array($user);

                    return $user['name'] ?? ($user['username'] ?? $user['id']);
                })
                ->link(function () {
                    if ($this->user) {
                        return admin_url('auth/users/'.$this->user['id']);
                    }
                });

            $grid->column('method', trans('admin.method'))
                ->label(XqwLog::$methodColors)
                ->filterByValue();

            $grid->column('path', trans('admin.uri'))->display(function ($v) {
                return "<code>$v</code>";
            })->filterByValue();

            $grid->column('ip', 'IP')->filterByValue();

            $grid->column('input')->display(function ($input) {
                $input = json_decode($input, true);

                if (empty($input)) {
                    return;
                }

                $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);

                if (empty($input)) {
                    return;
                }

                return '<pre class="dump" style="max-width: 500px">'.json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</pre>';
            });

            $grid->column('created_at', trans('admin.created_at'));

            $grid->model()->orderBy('id', 'DESC');

            $grid->disableCreateButton();
            $grid->disableQuickEditButton();
            $grid->disableEditButton();
            $grid->disableViewButton();
            $grid->showColumnSelector();
            $grid->setActionClass(Grid\Displayers\Actions::class);

            $grid->filter(function (Grid\Filter $filter) {
                $userModel = config('admin.database.users_model');

                $filter->in('user_id', trans('admin.user'))
                    ->multipleSelect($userModel::pluck('name', 'id'));

                $filter->equal('method', trans('admin.method'))
                    ->select(
                        array_combine(XqwLog::$methods, XqwLog::$methods)
                    );

                $filter->like('path', trans('admin.uri'));
                $filter->equal('ip', 'IP');
                $filter->between('created_at')->datetime();
            });
        });
    }

    public function destroy($id)
    {
        $ids = explode(',', $id);

        XqwLog::destroy(array_filter($ids));

        return JsonResponse::make()
            ->success(trans('admin.delete_succeeded'))
            ->refresh()
            ->send();
    }
}
