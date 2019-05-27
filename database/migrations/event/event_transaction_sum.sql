DROP EVENT IF EXISTS event_TransactionDailySum;
CREATE EVENT event_TransactionDailySum
	ON SCHEDULE EVERY '2' DAY
	STARTS '2019-05-27 12:40:00'
	DO
    BEGIN
      INSERT transactions_daily_sum (date_from, sum_amount)

       SELECT SUBDATE(CURDATE(),1) AS yesterday , SUM(transactions.amount) AS sum_amount   FROM transactions
        WHERE transactions.created_at >= SUBDATE(CURDATE(),1) ;

    END;
