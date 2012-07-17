<?php

/*
 * Copyright 2012 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * TODO: Should be final but isn't because of AphrontReloadResponse.
 *
 * @group aphront
 */
class AphrontRedirectResponse extends AphrontResponse {

  private $uri;

  public function setURI($uri) {
    $this->uri = $uri;
    return $this;
  }

  public function getURI() {
    return (string)$this->uri;
  }

  public function shouldStopForDebugging() {
    return PhabricatorEnv::getEnvConfig('debug.stop-on-redirect');
  }

  public function getHeaders() {
    $headers = array();
    if (!$this->shouldStopForDebugging()) {
      $headers[] = array('Location', $this->uri);
    }
    $headers = array_merge(parent::getHeaders(), $headers);
    return $headers;
  }

  public function buildResponseString() {
    if ($this->shouldStopForDebugging()) {
      $view = new PhabricatorStandardPageView();
      $view->setRequest($this->getRequest());
      $view->setApplicationName('Debug');
      $view->setTitle('Stopped on Redirect');

      $error = new AphrontErrorView();
      $error->setSeverity(AphrontErrorView::SEVERITY_NOTICE);
      $error->setTitle('Stopped on Redirect');

      $link = phutil_render_tag(
        'a',
        array(
          'href' => $this->getURI(),
        ),
        'Continue to: '.phutil_escape_html($this->getURI()));

      $error->appendChild(
        '<p>You were stopped here because <tt>debug.stop-on-redirect</tt> '.
        'is set in your configuration.</p>'.
        '<p>'.$link.'</p>');

      $view->appendChild($error);

      return $view->render();
    }

    return '';
  }

}
