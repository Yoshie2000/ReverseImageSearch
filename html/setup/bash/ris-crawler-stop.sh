for i in $(seq 1 15);
do
  sudo systemctl stop ris-crawler@"$i"
  sudo systemctl stop ris-crawler-img@"$i"
done