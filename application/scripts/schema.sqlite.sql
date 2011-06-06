CREATE TABLE tweetist (
    dataset   INTEGER     NOT NULL,
    tweet     INTEGER     NOT NULL,
    function  VARCHAR(16) NOT NULL,
    signature VARCHAR(16) NOT NULL,
    read      BOOLEAN     NOT NULL,
    PRIMARY KEY (dataset, tweet, function, signature)
)
