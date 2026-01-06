import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\ApiServicesController::edit
* @see app/Http/Controllers/Settings/ApiServicesController.php:22
* @route '/settings/api-services'
*/
export const edit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/settings/api-services',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::edit
* @see app/Http/Controllers/Settings/ApiServicesController.php:22
* @route '/settings/api-services'
*/
edit.url = (options?: RouteQueryOptions) => {
    return edit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::edit
* @see app/Http/Controllers/Settings/ApiServicesController.php:22
* @route '/settings/api-services'
*/
edit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::edit
* @see app/Http/Controllers/Settings/ApiServicesController.php:22
* @route '/settings/api-services'
*/
edit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::edit
* @see app/Http/Controllers/Settings/ApiServicesController.php:22
* @route '/settings/api-services'
*/
const editForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::edit
* @see app/Http/Controllers/Settings/ApiServicesController.php:22
* @route '/settings/api-services'
*/
editForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::edit
* @see app/Http/Controllers/Settings/ApiServicesController.php:22
* @route '/settings/api-services'
*/
editForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::update
* @see app/Http/Controllers/Settings/ApiServicesController.php:39
* @route '/settings/api-services'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/settings/api-services',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::update
* @see app/Http/Controllers/Settings/ApiServicesController.php:39
* @route '/settings/api-services'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::update
* @see app/Http/Controllers/Settings/ApiServicesController.php:39
* @route '/settings/api-services'
*/
update.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::update
* @see app/Http/Controllers/Settings/ApiServicesController.php:39
* @route '/settings/api-services'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ApiServicesController::update
* @see app/Http/Controllers/Settings/ApiServicesController.php:39
* @route '/settings/api-services'
*/
updateForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

const ApiServicesController = { edit, update }

export default ApiServicesController