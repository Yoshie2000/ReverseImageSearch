for i in $(seq 1 10);
do
  sudo systemctl start ris-crawler@"$i"
  sudo systemctl start ris-crawler-img@"$i"
done