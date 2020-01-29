for i in $(seq 1 15);
do
  sudo systemctl start ris-crawler@"$i"
  sudo systemctl start ris-crawler-img@"$i"
done