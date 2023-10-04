<?php
/**
 * Cache data.
 */

namespace Extendify\Assist\DataProvider;

use Extendify\Assist\Controllers\QuickLinksController;
use Extendify\Assist\Controllers\RecommendationsBannerController;
use Extendify\Assist\Controllers\RecommendationsController;
use Extendify\Assist\Controllers\SupportArticlesController;
use Extendify\Assist\Controllers\TasksController;
use Extendify\Assist\Controllers\TourController;
use Extendify\Assist\Controllers\WPController;
use Extendify\Http;

/**
 * The cache data class.
 */
class ResourceData
{
    /**
     * HTTP instance to be used for querying the data.
     *
     * @var Http
     */
    protected $http;

    /**
     * The cache group.
     *
     * @var string
     */
    protected $group = 'extendify';

    /**
     * The expiration interval.
     * (default 0, no expiration).
     *
     * @var float|int
     */
    protected $interval = 0;

    /**
     * Initiate the class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getHttpInstance();
    }


    /**
     * Register the cache schedule.
     *
     * @return void
     */
    public static function scheduleCache()
    {
        if (! wp_next_scheduled('extendify_cache_server_data')) {
            wp_schedule_event(
                current_time('timestamp'), // phpcs:ignore
                'daily',
                'extendify_cache_server_data'
            );
        }

        add_action('extendify_cache_server_data', [new ResourceData(), 'cache']);
    }


    /**
     * Regenerate and overwrite the cache.
     *
     * @return void
     */
    public function cache()
    {
        $endPoints = [
            'tasks' => $this->getResponseData(TasksController::fetchTasks()),
            'recommendations' => $this->getResponseData(RecommendationsController::fetchRecommendations()),
            'recommendationsBanner' => $this->getResponseData(RecommendationsBannerController::get()),
            'tours' => $this->getResponseData(TourController::fetchTours()),
            'supportArticles' => $this->getResponseData(SupportArticlesController::articles()),
            'supportArticleCategories' => $this->getResponseData(SupportArticlesController::categories()),
            'quickLinks' => $this->getResponseData(QuickLinksController::fetchQuickLinks()),
            'activePlugins' => $this->getResponseData(WPController::getActivePlugins()),
        ];

        foreach ($endPoints as $key => $endpoint) {
            $this->cacheData($key, $endpoint);
        }
    }

    /**
     * Return the data.
     *
     * @return array
     */
    public function getData()
    {
        return [
            'tasks' => $this->tasks(),
            'recommendations' => $this->recommendations(),
            'recommendationsBanner' => $this->recommendationsBanner(),
            'supportArticles' => $this->supportArticles(),
            'supportArticleCategories' => $this->supportArticleCategories(),
            'quickLinks' => $this->quickLinks(),
            'tours' => $this->tours(),
            'activePlugins' => $this->activePlugins(),
        ];
    }

    /**
     * Return the tasks.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function tasks()
    {
        $tasks = get_transient($this->group . '_' . __FUNCTION__);

        if ($tasks === false) {
            $tasks = $this->getResponseData(TasksController::fetchTasks());
            $this->cacheData(__FUNCTION__, $tasks);
        }

        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                if (is_array($task)
                    && array_key_exists('doneDependencies', $task)
                    && $task['doneDependencies']
                ) {
                    if ($task['slug'] === 'setup-givewp') {
                        $give = \get_option('give_onboarding', false);
                        if (isset($give['form_id']) && $give['form_id'] > 0) {
                            $this->markTaskCompleted($task['slug']);
                        }
                    }

                    if ($task['slug'] === 'setup-woocommerce-store') {
                        $woo = \get_option('woocommerce_onboarding_profile', false);
                        if ((isset($woo['completed']) && $woo['completed']) || (isset($woo['skipped']) && $woo['skipped'])) {
                            $this->markTaskCompleted($task['slug']);
                        }
                    }
                }//end if
            }//end foreach
        }//end if

        return $tasks;
    }

    /**
     * Return the recommendations.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function recommendations()
    {
        $recommendations = get_transient($this->group . '_' . __FUNCTION__);

        if ($recommendations === false) {
            $recommendations = $this->getResponseData(RecommendationsController::fetchRecommendations());
            $this->cacheData(__FUNCTION__, $recommendations);
        }

        return $recommendations;
    }

    /**
     * Returns the recommendations banner.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function recommendationsBanner()
    {
        $recommendationsBanner = get_transient($this->group . '_' . __FUNCTION__);

        if ($recommendationsBanner === false) {
            $recommendationsBanner = $this->getResponseData(RecommendationsBannerController::get());
            $this->cacheData(__FUNCTION__, $recommendationsBanner);
        }

        return $recommendationsBanner;
    }

    /**
     * Return the tours.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function tours()
    {
        $tours = get_transient($this->group . '_' . __FUNCTION__);

        if ($tours === false) {
            $tours = $this->getResponseData(TourController::fetchTours());
            $this->cacheData(__FUNCTION__, $tours);
        }

        return $tours;
    }

    /**
     * Return the support articles.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function supportArticles()
    {
        $supportArticles = get_transient($this->group . '_' . __FUNCTION__);

        if ($supportArticles === false) {
            $supportArticles = $this->getResponseData(SupportArticlesController::articles());
            $this->cacheData(__FUNCTION__, $supportArticles);
        }

        return $supportArticles;
    }

    /**
     * Return the support articles categories.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function supportArticleCategories()
    {
        $supportArticlesCategories = get_transient($this->group . '_' . __FUNCTION__);

        if ($supportArticlesCategories === false) {
            $supportArticlesCategories = $this->getResponseData(SupportArticlesController::categories());
            $this->cacheData(__FUNCTION__, $supportArticlesCategories);
        }

        return $supportArticlesCategories;
    }

    /**
     * Return the quick links.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function quickLinks()
    {
        $quickLinks = get_transient($this->group . '_' . __FUNCTION__);

        if ($quickLinks === false) {
            $quickLinks = $this->getResponseData(QuickLinksController::fetchQuickLinks());
            $this->cacheData(__FUNCTION__, $quickLinks);
        }

        return $quickLinks;
    }

    /**
     * Return the active plugins.
     *
     * @return mixed|\WP_REST_Response
     */
    protected function activePlugins()
    {
        $activePlugins = get_transient($this->group . '_' . __FUNCTION__);

        if ($activePlugins === false) {
            $activePlugins = $this->getResponseData(WPController::getActivePlugins());
            $this->cacheData(__FUNCTION__, $activePlugins);
        }

        return $activePlugins;
    }

    /**
     * This function will check for the validity of the data, if the request was a success then
     * we store the results in the database, if not, we just ignore it.
     *
     * @param string $functionName The function name that we use in the store.
     * @param array  $data         The extracted data returned from the HTTP request.
     * @return void
     */
    protected function cacheData($functionName, $data)
    {
        if (!empty($data)) {
            set_transient($this->group . '_' . $functionName, $data, $this->interval);
        }
    }

    /**
     * This function will return the data from WP_REST_Response object if there is no error,
     * or return an empty array.
     *
     * @param \WP_REST_Response $data The response we need to filter.
     * @return array|mixed
     */
    protected function getResponseData($data)
    {
        if (!is_wp_error($data->get_data())
            && is_array($data->get_data())
            && array_key_exists('success', $data->get_data())
            && array_key_exists('data', $data->get_data())
            && $data->get_data()['success']
        ) {
            return $data->get_data()['data'];
        }

        return [];
    }

    /**
     * Create an HTTP instance that we can use locally.
     *
     * @return void
     */
    protected function getHttpInstance()
    {
        $request = new \WP_REST_Request();

        $request->set_headers([
            'x_wp_nonce' => \wp_create_nonce('wp_rest'),
            'x_extendify' => true,
            'referer' => get_home_url(),
        ]);

        /**
         * This constant should have one of the following values:
         *
         * 1. x_extendify_assist_dev_mode.
         * 2. x_extendify_assist_local_mode.
         * 3. x_extendify_assist.
         *
         * default value is (x_extendify_assist).
         */

        if (!defined('EXTENDIFY_ASSIST_SERVER')) {
            define('EXTENDIFY_ASSIST_SERVER', 'x_extendify_assist');
        }

        $request->set_header(EXTENDIFY_ASSIST_SERVER, 'true');

        Http::init($request);
    }

    /**
     * Mark a given task as completed.
     *
     * @param string $slug The task slug that we need to search for.
     * @return void
     */
    protected function markTaskCompleted($slug)
    {
        $data = get_option('extendify_assist_tasks', []);

        if (!array_key_exists('state', $data)) {
            return;
        }

        if (!in_array($slug, array_column($data['state']['completedTasks'], 'id'), true)) {
            $data['state']['completedTasks'][] = [
                'id' => $slug,
                'completedAt' => gmdate('Y-m-d\TH:i:s.v\Z'),
            ];
            update_option('extendify_assist_tasks', $data);
        }
    }
}
