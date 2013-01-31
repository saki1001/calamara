<?php
/**
This file is part of the podcast.de WordPress plugin.

The podcast.de WordPress plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

The podcast.de WordPress plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with the podcast.de WordPress plugin.  If not, see http://www.gnu.org/licenses/.
*/

/**
 *
 * @package podcast.de WordPress plugin
 * @author  Fabio Bacigalupo <wordpress-plugin@podcast.de>
 * @copyright Fabio Bacigalupo 2012
 * @version $Revision: 0.1 $
 * @since PHP5.2.12
 */
interface PodcastDePluginStandardsInterface {

    /**
     * This acts as controller
     *
     */
    public function process();

    /**
     * Returns internally used short version of plugin name
     * used as translation identifier
     *
     */
    function getIdentifier();

    /**
     * Returns plugin name
     *
     */
    function getName();

    /**
     * Returns version information
     *
     */
    function getVersion();
}
?>