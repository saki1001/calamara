<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( !class_exists('SilkyPress_FormsHelper') ) {
/**
 * SilkyPress_FormsHelper 
 */
class SilkyPress_FormsHelper {

    public $label_class = 'col-sm-3 control-label';
    public $non_label_class = 'col-sm-9';
    public $plugin_url = '';

    public function input( $type, $settings = array() ) {
        $allowed_types = array( 'text', 'radio', 'input_text', 'buttons', 'input_color', 'checkbox', 'header' );

        if ( ! in_array( $type, $allowed_types ) ) {
            return;
        }
        if ( !isset($settings['label'] )) return;
        if ( !isset($settings['name'] )) return;
        if ( isset($settings['disabled']) && $settings['disabled'] ) {
            $settings['disabled'] = ' disabled';
        } else {
            $settings['disabled'] = '';
        }
        call_user_func( array($this, $type), $settings );
    }


    public function text( $args = array() ) {
        if ( ! isset($args['value'] ) ) $args['value'] = '';
        if ( ! isset($args['description'] ) ) $args['description'] = '';
        ?>
            <div class="form-group<?php echo $args['disabled']; ?>">
            <?php echo $this->label( $args ); ?>
            <div class="input-group" style="width: auto;">
                <?php echo $args['value']; ?>
                </div>
                <?php $this->helpblock( $args ); ?>
			</div>
        <?php
    }
    
    public function radio($args = array(), $inline = false) {
        if ( !isset($args['values'] ) || count($args['values']) == 0 ) return;
        if ( !isset($args['value'] ) ) $args['value'] = '';
        if ( !isset($args['style'] ) ) $args['style'] = '';
        if ( !isset($args['active'] ) ) $args['active'] = '';
        ?>
            <div class="form-group<?php if(!empty($args['disabled'])) echo ' disabled-short'; ?>">
            <?php echo $this->label( $args ); ?>
            <div class="<?php echo $this->non_label_class; ?>">
                <?php foreach ($args['values'] as $_id => $_label) : ?>
                <div class="radio<?php if($args['style'] == 'inline') echo '-inline'; ?><?php echo $args['disabled'] ?>">
                  <label>
                  <input type="radio" name="<?php echo $args['name'] ?>" id="<?php echo $_id ?>" value="<?php echo $_id ?>" <?php echo ($_id == $args['value']) ? 'checked=""' : ''; echo $args['disabled']; ?>>
                  <?php echo $_label ?>
                  </label>
                </div>
                <?php endforeach; ?>
                <?php $this->helpblock( $args ); ?>
			</div>		    
			</div>		    
        <?php
    }

    public function input_text( $args = array() ) {
        if ( ! isset($args['value'] ) ) $args['value'] = '';
        if ( ! isset($args['description'] ) ) $args['description'] = '';
        ?>
            <div class="form-group<?php echo $args['disabled']; ?>">
            <?php echo $this->label( $args ); ?>
            <div class="input-group">
            <input type="text" class="form-control" id="<?php echo $args['name']?>" name="<?php echo $args['name'] ?>" value="<?php echo $args['value'] ?>"<?php echo $args['disabled']; ?> />
            <?php if (isset($args['post_input'])) : ?><span class="input-group-addon"><?php echo $args['post_input'] ?></span><?php endif; ?>
            </div>
                <?php $this->helpblock( $args ); ?>
			</div>
        <?php
    }


    public function input_color( $args = array() ) {
        if ( ! isset($args['value'] ) ) $args['value'] = '';
        ?>
            <div class="form-group<?php echo $args['disabled']; ?>">
            <?php echo $this->label( $args ); ?>
				<div class="input-group">
                <input type="color" class="form-control" id="<?php echo $args['name'] ?>" name="<?php echo $args['name'] ?>" value="<?php echo $args['value'] ?>"<?php echo $args['disabled']; ?> />
                <span class="input-group-addon" id="color-text-color-hex"><?php echo $args['value'] ?></span>
				</div>
                <?php $this->helpblock( $args ); ?>
			</div>

        <?php
    }

    public function checkbox( $args = array() ) {
        if ( ! isset($args['value'] ) ) $args['value'] = false;
        ?>
            <div class="form-group<?php echo $args['disabled']; ?>">
            <?php echo $this->label( $args ); ?>
                  <div class="input-group">
                    <label>
                    <input type="checkbox" id="<?php echo $args['name'] ?>" name="<?php echo $args['name'] ?>" <?php echo ($args['value'] == true) ? 'checked=""' : '' ?><?php echo $args['disabled'] ?> />
                    </label>
                   </div>
                <?php $this->helpblock( $args ); ?>
            </div>
        <?php
    }

    public function header( $args = array() ) {
        ?>
            <h4 class="col-sm-5"><?php echo $args['label']; ?></h4><div style="clear: both;"></div>
        <?php
    }

    public function buttons( $args = array() ) {
        if ( ! isset($args['values'] ) || count($args['values']) == 0 ) return;
        if ( ! isset($args['value'] ) ) $args['value'] = '';
        if ( ! isset($args['style'] ) ) $args['style'] = '';
        ?>
        <div class="form-group<?php if(!empty($args['disabled'])) echo ' disabled-short'; ?>">
        <?php echo $this->label( $args ); ?>
          <div class="btn-group <?php echo $args['disabled'] ?>" data-toggle="buttons" id="btn-group-style-circle">
            <?php foreach( $args['values'] as $_id => $_value ) : ?>
                <?php $tooltip = (!empty($_value[1])) ? ' data-toggle="tooltip" data-placement="top" title="'.$_value[1].'" data-original-title="' . $_value[1] . '"' : ''; ?>
            <label class="btn btn-default<?php echo ($args['value'] == $_id) ? ' active' : '' ?> "<?php echo $args['disabled'] ?>>
            <input type="radio" name="<?php echo $args['name'] ?>" id="<?php echo $_id ?>" value="<?php echo $_id ?>" <?php echo  ($args['value'] == $_id) ? 'checked' : '' ?> />
            <div class="icon-in-label ndd-spot-icon icon-style-1" <?php echo $tooltip; ?>>
              <div class="ndd-icon-main-element">
                <?php if($args['style'] == 'full') : ?>
                    <?php echo $_value[0];?>
                <?php else : ?>
                    <img src="<?php echo $this->plugin_url . 'assets' . $_value[0] ?>" />
                <?php endif; ?>
              </div>
            </div>
            </label>
            <?php endforeach; ?>
                <?php $this->helpblock( $args ); ?>
          </div>
        </div>
        <?php
    }

    public function label( $args = array() ) {
        $output = '<label for="'.$args['name'].'" class="'.$this->label_class.'">'.$args['label'];
        if ( isset ( $args['description'] ) && !empty( $args['description'] ) ) {
            $output .= ' <img src="'.$this->plugin_url.'assets/images/question_mark.svg" data-toggle="tooltip" data-placement="top" title="'.$args['description'].'" data-original-title="'.$args['description'].'" />';
        }
        $output .= '</label>' . "\n";
        return $output;
    }

    public function helpblock( $args = array() ) {
        return true;
        if ($args['disabled'] == ' disabled' && isset($args['description']) ) { ?>
            <span class="help-block"><?php echo $args['description'] ?></span>
        <?php }
    }

}
}

?>
