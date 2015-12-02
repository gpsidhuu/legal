<?php

class xsErrors {
	static function throw_error( $error_id ) {
		$errors = array();
		$errors['game_in_progress'] = 'There is another trivia in play.<br/><a class="b-btn-white discard_quiz">Discard & Start new Trivia</a>';
		$errors['need_login'] = 'Only registered user can play the trivia';
		$errors['cheating'] = 'Cheating HUH!';
		$errors['no_code'] = 'All coupons have been used. Try again later';
		$errors['no_question'] = 'No question available right now. Please check back later.';
		$errors['quiz_taken'] = 'You can play one trivia per coupon';
		$errors['wrong_answer'] = 'Sorry, your answer is wrong.Thanks for playing the trivia. Good luck next time';
		$errors['failed_mark_used'] = "Failed to update record. Error 1000";
		$errors['game_over'] = "Times Up.Game Over";
		echo json_encode( array(
			'status' => FALSE,
			'code'   => $error_id,
			'msg'    => $errors[ $error_id ]
		) );
		die;
	}
}