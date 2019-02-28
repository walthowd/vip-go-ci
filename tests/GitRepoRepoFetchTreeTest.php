<?php

require_once( __DIR__ . '/IncludesForTests.php' );

use PHPUnit\Framework\TestCase;

final class GitRepoRepoFetchTreeTest extends TestCase {
	var $options_git = array(
		'git-path'			=> null,
		'github-repo-url'		=> null,
		'repo-owner'			=> null,
		'repo-name'			=> null,
		'github-token'			=> null,
	);

	var $options_git_repo_tests = array(
		'commit-test-repo-fetch-tree-1'	=> null,
		'commit-test-repo-fetch-tree-2'	=> null,
	);

	protected function setUp() {
		vipgoci_unittests_get_config_values(
			'git',
			$this->options_git
		);

		vipgoci_unittests_get_config_values(
			'git-repo-tests',
			$this->options_git_repo_tests
		);

		$this->options = array_merge(
			$this->options_git,
			$this->options_git_repo_tests
		);
	}

	/**
	 * @covers ::vipgoci_gitrepo_fetch_tree
	 */
	public function testRepoFetchTree1() {
		foreach ( $this->options as $option_key => $option_value ) {
			if ( 'github-token' === $option_key ) {
				continue;
			}

			if ( null === $option_value ) {
				$this->markTestSkipped(
					'Skipping test, not configured correctly'
				);

				return;
			}
		}

		$this->options['commit'] =
			$this->options['commit-test-repo-fetch-tree-1'];

		ob_start();

		$this->options['local-git-repo'] =
			vipgoci_unittests_setup_git_repo(
				$this->options
			);


		if ( false === $this->options['local-git-repo'] ) {
			$this->markTestSkipped(
				'Could not set up git repository: ' .
					ob_get_flush()
			);

			return;
		}

		$this->options['token'] =
			$this->options['github-token'];

		$ret1 = vipgoci_gitrepo_fetch_tree(
			$this->options,
			$this->options['commit-test-repo-fetch-tree-1'],
			null
		);

		ob_end_clean();

		$this->assertEquals(
			$ret1,
			array(
				'README.md',
				'file-1.txt',
			)
		);
	}

	/**
	 * @covers ::vipgoci_gitrepo_fetch_tree
	 */
	public function testRepoFetchTree2() {
		foreach ( $this->options as $option_key => $option_value ) {
			if ( 'github-token' === $option_key ) {
				continue;
			}

			if ( null === $option_value ) {
				$this->markTestSkipped(
					'Skipping test, not configured correctly'
				);

				return;
			}
		}

		$this->options['commit'] =
			$this->options['commit-test-repo-fetch-tree-2'];

		ob_start();

		$this->options['local-git-repo'] =
			vipgoci_unittests_setup_git_repo(
				$this->options
			);


		if ( false === $this->options['local-git-repo'] ) {
			$this->markTestSkipped(
				'Could not set up git repository: ' .
					ob_get_flush()
			);
		}

		$this->options['token'] =
			$this->options['github-token'];

		$ret2 = vipgoci_gitrepo_fetch_tree(
			$this->options,
			$this->options['commit-test-repo-fetch-tree-2'],
			array(
				'file_extensions' => array( 'txt' )
			)
		);

		ob_end_clean();

		$this->assertEquals(
			$ret2,
			array(
				'file-1.txt',
			)
		);
	}
}
