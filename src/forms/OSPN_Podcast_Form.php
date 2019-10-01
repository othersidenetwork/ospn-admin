<?php

namespace OSPN\Form;

/**
 * Class OSPN_Podcast_Form
 *
 * This class represents the HTML form used to update a plugin's data.
 * 
 * @package OSPN\Form
 */
class OSPN_Podcast_Form
{
	/** @var  int The podcast's ID */
	public $blog_id;

	/** @var  string The podcast's name */
	public $podcast_name;

	/** @var  string The podcast's slug */
	public $podcast_slug;

	/** @var  string The podcast's tagline */
	public $tagline;

	/** @var  string The podcast's logo's URL */
	public $logo;

	/** @var  string The podcast's description */
	public $description;

	/** @var  int The first host's ID */
	public $host_id;

	/** @var  int The second host's ID */
	public $host2_id;

	/** @var  object[] The hosts */
	public $hosts;

	/** @var  string The podcast's website's URL */
	public $website;

	/** @var  string The podcast's contact email adresse */
	public $contact;

	/** @var  string The podcast's RSS feed's URL */
	public $podcast_feed;

	/** @var  bool The podcast's state */
	public $active;

	/** @var  string Origin of this form : 'admin' means network admin, anything else means site admin. */
	public $origin;

	/** @var  object[] Roles */
	public $roles;

    /**
     * @param $options array
     */
    public function dropdown_roles($options) {
        echo '<select name="' . $options['name'] . '" id="' . $options['name'] . '" class="">';
        foreach ($this->roles as $role) {
            echo '<option value="' . $role->role_id . '"' . ($options['selected'] == $role->role_id ? 'selected="selected"' : '') . '>' . $role->role_name . '</option>';
        }
        echo '</select>';
    }
}