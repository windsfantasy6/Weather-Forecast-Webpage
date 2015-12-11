#include <iostream>
using namespace std;

int main() {
  int l,p,v;
  while (cin >> l >> p >> v) {
    if (l == 0 && p == 0 && v == 0) break;
    cout << v / p * l + min(v % p,l) << endl;
  }
  return 0;
}
